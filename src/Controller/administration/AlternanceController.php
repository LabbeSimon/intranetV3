<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/administration/AlternanceController.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 08/10/2021 19:11
 */

namespace App\Controller\administration;

use App\Classes\MyExport;
use App\Controller\BaseController;
use App\Entity\Alternance;
use App\Entity\Annee;
use App\Entity\Constantes;
use App\Entity\Etudiant;
use App\Entity\Personnel;
use App\Form\AlternanceType;
use App\Repository\AlternanceRepository;
use App\Repository\EtudiantRepository;
use function count;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/administration/alternance')]
class AlternanceController extends BaseController
{
    #[Route(path: '/init/all/{annee}', name: 'administration_alternance_init_all')]
    public function initAll(EtudiantRepository $etudiantRepository, AlternanceRepository $alternanceRepository, Annee $annee): RedirectResponse
    {
        $this->denyAccessUnlessGranted('MINIMAL_ROLE_ASS', $annee);
        $etudiants = $etudiantRepository->findByAnnee($annee);
        /** @var Etudiant $etudiant */
        foreach ($etudiants as $etudiant) {
            $exist = $alternanceRepository->findBy([
                'etudiant' => $etudiant->getId(),
                'anneeUniversitaire' => $annee->getAnneeUniversitaire(),
                'annee' => $annee->getId(),
            ]);
            if (0 === count($exist)) {
                $alternance = new Alternance();
                $alternance->setEtudiant($etudiant);
                $alternance->setAnneeUniversitaire($annee->getDiplome()?->getAnneeUniversitaire());
                $alternance->setAnnee($annee);
                $alternance->setEtat('init');
                $this->entityManager->persist($alternance);
            }
        }
        $this->entityManager->flush();
        $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'alternance.init.all.success.flash');

        return $this->redirectToRoute('administration_alternance_index', ['annee' => $annee->getId()]);
    }

    #[Route(path: '/init/{annee}/{action}/{etudiant}', name: 'administration_alternance_init')]
    public function init(Etudiant $etudiant, $action, Annee $annee): RedirectResponse
    {
        $this->denyAccessUnlessGranted('MINIMAL_ROLE_ASS', $annee);
        $alternance = new Alternance();
        $alternance->setEtudiant($etudiant);
        $alternance->setAnneeUniversitaire($annee->getDiplome()?->getAnneeUniversitaire());
        $alternance->setAnnee($annee);
        if ('init-false' === $action) {
            $alternance->setEtat(Alternance::ALTERNANCE_ETAT_SANS);
        } else {
            $alternance->setEtat(Alternance::ALTERNANCE_ETAT_INITIALISE);
        }
        $this->entityManager->persist($alternance);
        $this->entityManager->flush();
        $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'alternance.init.one.success.flash');

        return $this->redirectToRoute('administration_alternance_index', ['annee' => $annee->getId()]);
    }

    #[Route(path: '/export/{annee}.{_format}', name: 'administration_alternance_export', requirements: ['_format' => 'csv|xlsx|pdf'], methods: 'GET')]
    public function export(MyExport $myExport, AlternanceRepository $alternanceRepository, Annee $annee, $_format): Response
    {
        $this->denyAccessUnlessGranted('MINIMAL_ROLE_ASS', $annee);
        $actualites = $alternanceRepository->getByAnneeAndAnneeUniversitaire($annee,
            $annee->getDiplome()?->getAnneeUniversitaire());

        return $myExport->genereFichierGenerique(
            $_format,
            $actualites,
            'alternances',
            ['alternance_administration', 'utilisateur'],
            [
                'entreprise' => ['libelle'],
                'tuteur' => ['nom', 'prenom', 'fonction', 'telephone', 'email', 'portable'],
                'etudiant' => ['nom', 'prenom', 'mailUniv'],
                'tuteurUniversitaire' => ['nom', 'prenom', 'mailUniv'],
                'typeContrat',
                'dateDebut',
                'dateFin',
            ]
        );
    }

    #[Route(path: '/details/{id}', name: 'administration_alternance_show', methods: 'GET')]
    public function show(Alternance $alternance): Response
    {
        $this->denyAccessUnlessGranted('MINIMAL_ROLE_ASS', $alternance->getAnnee());

        return $this->render('administration/alternance/show.html.twig', ['alternance' => $alternance]);
    }

    #[Route(path: '/{id}/edit', name: 'administration_alternance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Alternance $alternance): Response
    {
        $this->denyAccessUnlessGranted('MINIMAL_ROLE_ASS', $alternance->getAnnee());
        $form = $this->createForm(AlternanceType::class, $alternance,
            ['departement' => $this->dataUserSession->getDepartement()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'alternance.edit.success.flash');

            return $this->redirectToRoute('administration_alternance_edit', ['id' => $alternance->getId()]);
        }

        return $this->render('administration/alternance/edit.html.twig', [
            'alternance' => $alternance,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{annee}', name: 'administration_alternance_index', requirements: ['annee' => '\d+'], methods: 'GET')]
    public function index(EtudiantRepository $etudiantRepository, AlternanceRepository $alternanceRepository, Annee $annee): Response
    {
        $this->denyAccessUnlessGranted('MINIMAL_ROLE_ASS', $annee);
        $etudiants = $etudiantRepository->findByAnnee($annee);

        return $this->render('administration/alternance/index.html.twig',
            [
                'alternances' => $alternanceRepository->getByAnneeAndAnneeUniversitaireArray($annee,
                    $annee->getDiplome()?->getAnneeUniversitaire()),
                'annee' => $annee,
                'etudiants' => $etudiants,
            ]);
    }

    #[Route(path: '/{id}', name: 'administration_alternance_delete', methods: 'DELETE')]
    public function delete(Request $request, Alternance $alternance): Response
    {
        $this->denyAccessUnlessGranted('MINIMAL_ROLE_ASS', $alternance->getAnnee());
        $id = $alternance->getId();
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $this->entityManager->remove($alternance);
            $this->entityManager->flush();
            $this->addFlashBag(
                Constantes::FLASHBAG_SUCCESS,
                'alternance.delete.success.flash'
            );

            return $this->json($id, Response::HTTP_OK);
        }
        $this->addFlashBag(Constantes::FLASHBAG_ERROR, 'alternance.delete.error.flash');

        return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    #[Route(path: '/update/tuteur-universitaire/{alternance}/{personnel}', name: 'administration_alternance_update_tuteur_universitaire', options: ['expose' => true])]
    public function updateTuteurUniversitaire(Alternance $alternance, Personnel $personnel): JsonResponse
    {
        //todo: a revoir $this->denyAccessUnlessGranted('MINIMAL_ROLE_ASS', $alternance->getAnnee());
        $alternance->setTuteurUniversitaire($personnel);
        $this->entityManager->persist($alternance);
        $this->entityManager->flush();

        return $this->json(true);
    }
}
