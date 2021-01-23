<?php
// Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/administration/projet/ProjetEtudiantController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 23/01/2021 14:47

namespace App\Controller\administration\projet;

use App\Classes\Pdf\MyPDF;
use App\Controller\BaseController;
use App\Entity\Constantes;
use App\Entity\Etudiant;
use App\Entity\ProjetEtudiant;
use App\Entity\ProjetPeriode;
use App\Form\ProjetEtudiantType;
use App\Classes\MyProjetEtudiant;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 *  * @Route("/administration/projet/etudiant")
 */
class ProjetEtudiantController extends BaseController
{
    /**
     * @Route("/{id}/edit", name="administration_projet_etudiant_edit", methods="GET|POST")
     * @param Request        $request
     * @param ProjetEtudiant $projetEtudiant
     *
     * @return Response
     */
    public function edit(Request $request, ProjetEtudiant $projetEtudiant): Response
    {
        $form = $this->createForm(ProjetEtudiantType::class, $projetEtudiant, [
            'semestre' => $projetEtudiant->getProjetPeriode()->getSemestre()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'projet_etudiant.edit.success.flash');

            if ($request->request->get('btn_update') !== null) {
                return $this->redirectToRoute('administration_projet_periode_gestion', [
                    'uuid' => $projetEtudiant->getProjetPeriode()->getUuidString()
                ]);
            }

            return $this->redirectToRoute('administration_projet_etudiant_edit', ['id' => $projetEtudiant->getId()]);
        }

        return $this->render('administration/projet/projet_etudiant/edit.html.twig', [
            'projetEtudiant' => $projetEtudiant,
            'form'           => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_projet_etudiant_delete", methods="DELETE")
     * @param Request        $request
     * @param ProjetEtudiant $projetEtudiant
     *
     * @return Response
     */
    public function delete(Request $request, ProjetEtudiant $projetEtudiant): Response
    {
        $id = $projetEtudiant->getId();
        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $this->entityManager->remove($projetEtudiant);
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'projet_etudiant.delete.success.flash');
        } else {
            $this->addFlashBag(Constantes::FLASHBAG_ERROR, 'projet_etudiant.delete.error.flash');
        }

        return $this->json([
            'redirect' => true,
            'url'      => $projetEtudiant->getProjetPeriode() !== null ? $this->generateUrl('administration_projet_periode_gestion',
                ['uuid' => $projetEtudiant->getProjetPeriode()->getUuidString()]) : $this->generateUrl('administration_index')
        ]);
    }

    /**
     * @param MyProjetEtudiant $myProjetEtudiant
     * @param ProjetPeriode    $projetPeriode
     * @param Etudiant         $etudiant
     * @param                  $etat
     *
     * @return RedirectResponse
     * @throws NonUniqueResultException
     * @Route("/change-etat/{projetPeriode}/{etudiant}/{etat}", name="administration_projet_etudiant_change_etat")
     * @ParamConverter("projetPeriode", options={"mapping": {"projetPeriode": "uuid"}})
     */
    public function changeEtat(
        MyProjetEtudiant $myProjetEtudiant,
        ProjetPeriode $projetPeriode,
        Etudiant $etudiant,
        $etat
    ) {
        $myProjetEtudiant->changeEtat($projetPeriode, $etudiant, $etat);
        $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'projet_etudiant.change_etat.success.flash');

        return $this->redirectToRoute('administration_projet_periode_gestion',
            ['uuid' => $projetPeriode->getUuidString()]);
    }


    /**
     * @Route("/convention/pdf/{id}", name="administration_projet_etudiant_convention_pdf", methods="GET")
     * @param MyPDF          $myPDF
     * @param ProjetEtudiant $projetEtudiant
     *
     * @return PdfResponse
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function conventionPdf(MyPDF $myPDF, ProjetEtudiant $projetEtudiant): PdfResponse
    {
        return $myPDF::generePdf('pdf/projetTutore/conventionProjet.html.twig',
            [
                'projetEtudiant' => $projetEtudiant,
            ],
            'Convention-' . $projetEtudiant->getEtudiants()[0]->getNom(),
            $this->dataUserSession->getDepartement()
        );
    }
}
