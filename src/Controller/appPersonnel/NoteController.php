<?php
// Copyright (C) 11 / 2019 | David annebicque | IUT de Troyes - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetv3/src/Controller/appPersonnel/NoteController.php
// @author     David Annebicque
// @project intranetv3
// @date 19/11/2019 07:35
// @lastUpdate 17/11/2019 19:18

namespace App\Controller\appPersonnel;

use App\Controller\BaseController;
use App\Entity\Evaluation;
use App\Entity\Matiere;
use App\Entity\Semestre;
use App\Form\EvaluationsPersonnelsType;
use App\Form\EvaluationType;
use App\MesClasses\MyEtudiant;
use App\MesClasses\MyEvaluation;
use App\MesClasses\MyEvaluations;
use App\MesClasses\MyExport;
use App\MesClasses\MyUpload;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotesController
 * @package App\Controller
 * @Route("/application/personnel/note")
 *
 * @IsGranted("ROLE_PERMANENT")
 */
class NoteController extends BaseController
{
    /**
     * @Route("/{matiere}/{index}", name="application_personnel_note_index", requirements={"matiere"="\d+"})
     * @param MyEvaluations $myEvaluations
     * @param Matiere       $matiere
     *
     * @param int           $index
     *
     * @return Response
     */
    public function index(MyEvaluations $myEvaluations, Matiere $matiere, $index = 0): Response
    {
        if ($matiere->getSemestre()) {
            $myEvaluations->setMatiere($matiere);
            $myEvaluations->getEvaluationsMatiere($matiere->getSemestre()->getAnneeUniversitaire());

            return $this->render('appPersonnel/note/index.html.twig', [
                'matiere'     => $matiere,
                'evaluations' => $myEvaluations,
                'indexEval'   => $index
            ]);
        }

        return $this->redirectToRoute('erreur_666');
    }

    /**
     * @Route("/saisie/etape-1/{matiere}", name="application_personnel_note_saisie", requirements={"matiere"="\d+"})
     * @param Request $request
     * @param Matiere $matiere
     *
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function saisie(Request $request, Matiere $matiere)
    {
        if ($matiere !== null && $matiere->getUe() !== null) {
            $evaluation = new Evaluation($this->getConnectedUser(), $matiere, $this->dataUserSession->getDepartement());
            $form = $this->createForm(
                EvaluationType::class,
                $evaluation,
                [
                    'departement'     => $this->dataUserSession->getDepartement(),
                    'semestre'        => $matiere->getUe()->getSemestre(),
                    'matiereDisabled' => false,
                    'locale'          => $request->getLocale(),
                    'attr'            => [
                        'data-provide' => 'validation'
                    ]
                ]
            );

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($evaluation);
                $this->entityManager->flush();

                return $this->redirectToRoute(
                    'application_personnel_note_saisie_2',
                    ['uuid' => $evaluation->getUuidString()]
                );
            }

            return $this->render('appPersonnel/note/saisie.html.twig', [
                'matiere' => $matiere,
                'form'    => $form->createView()
            ]);
        }

        return $this->redirectToRoute('erreur_666');
    }

    /**
     * @Route("/saisie/etape-2/{uuid}", name="application_personnel_note_saisie_2",
     *                                        requirements={"matiere"="\d+"})
     * @param MyEvaluation $myEvaluation
     * @param Evaluation   $evaluation
     * @ParamConverter("evaluation", options={"mapping": {"uuid": "uuid"}})
     *
     * @return Response
     */
    public function saisieNotes(MyEvaluation $myEvaluation, Evaluation $evaluation): Response
    {
        $notes = $myEvaluation->setEvaluation($evaluation)->getNotesTableau();

        return $this->render('appPersonnel/note/saisie_2.html.twig', [
            'evaluation' => $evaluation,
            'notes'      => $notes
        ]);
    }

    /**
     * @param MyEtudiant $myEtudiant
     * @param Request    $request
     *
     * @param Evaluation $evaluation
     * @ParamConverter("evaluation", options={"mapping": {"uuid": "uuid"}})
     *
     * @return RedirectResponse|Response
     * @Route("/sauvegarde/{uuid}",
     *     name="application_personnel_note_ajax_saisie",
     *     methods={"POST|GET"},
     *     options={"expose":true})
     * @throws Exception
     */
    public function enregistreNoteAction(MyEtudiant $myEtudiant, Request $request, Evaluation $evaluation)
    {
        //vérifier $this->get('request')->request->get('notes)['notes']
        $tnote = $request->request->get('notes')['notes'];

        foreach ($tnote as $iValue) {
            $myEtudiant->setUuidEtudiant($iValue['id']);
            $myEtudiant->addNote($evaluation, $iValue);
        }

        return new Response();
    }

    /**
     * @Route("/import/{evaluation}", name="application_personnel_note_import", requirements={"evaluation"="\d+"})
     * @param Request      $request
     * @param MyUpload     $myUpload
     * @param MyEvaluation $myEvaluation
     * @param Evaluation   $evaluation
     *
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function import(
        Request $request,
        MyUpload $myUpload,
        MyEvaluation $myEvaluation,
        Evaluation $evaluation
    ): Response {
        //upload
        $fichier = $myUpload->upload($request->files->get('fichier_import'), 'temp/');

        //traitement de l'import des notes.
        $notes = $myEvaluation->importEvaluation($evaluation, $fichier);
        $this->addFlashBag('success', 'import_note_a_verifier');

        return $this->render('appPersonnel/note/saisie_2.html.twig', [
            'evaluation' => $evaluation,
            'notes'      => $notes,
            'import'     => true
        ]);
    }

    /**
     * @Route("/modele-import/{evaluation}", name="application_personnel_note_import_modele", methods="GET")
     * @param MyExport $myExport
     * @param Semestre $semestre
     *
     * @return Response|null
     */
    public function modeleImport(MyExport $myExport, Evaluation $evaluation): ?Response
    {
        return $myExport->genereModeleImportNote($evaluation);
    }

    /**
     * @Route("/ajax/gere/{uuid}", name="application_personnel_note_ajax_autorise", methods="GET")
     * @ParamConverter("evaluation", options={"mapping": {"uuid": "uuid"}})
     * @param Evaluation $evaluation
     *
     * @return Response
     */
    public function ajaxAddPersonneAction(Evaluation $evaluation): Response
    {
        if ($evaluation !== null) //todo: tester si c'est le prof ou un admin...
        {
            $form = $this->createForm(EvaluationsPersonnelsType::class, $evaluation, [
                'attr'     => ['id' => 'formPersonne', 'class' => 'form-horizontal'],
                'semestre' => $evaluation->getMatiere()->getUE()->getSemestre()
            ]);

            return $this->render('appPersonnel/note/addPersonne.html.twig',
                [
                    'form' => $form->createView(),
                    'eval' => $evaluation
                ]);
        }

        return $this->render('bundles/TwigBundle/Exception/error666.html.twig');
    }
}
