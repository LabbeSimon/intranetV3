<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/administration/AlternanceFicheSuiviController.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 07/02/2021 11:11
 */


namespace App\Controller\administration;

use App\Classes\MyAlternanceFicheSuivi;
use App\Controller\BaseController;
use App\Entity\Alternance;
use App\Entity\AlternanceFicheSuivi;
use App\Entity\Constantes;
use App\Form\AlternanceFicheSuiviType;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration/alternance/fiche/suivi")
 */
class AlternanceFicheSuiviController extends BaseController
{
    /**
     * @Route("/new/{alternance}", name="administration_alternance_fiche_suivi_new", methods={"GET","POST"})
     *
     * @throws Exception
     */
    public function new(Request $request, Alternance $alternance): Response
    {
        $alternanceFicheSuivi = new AlternanceFicheSuivi($alternance);
        $form = $this->createForm(AlternanceFicheSuiviType::class, $alternanceFicheSuivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($alternanceFicheSuivi);
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'fiche_suivi.new.success.flash');

            return $this->redirectToRoute('administration_alternance_show', ['alternance' => $alternance->getId()]);
        }

        return $this->render('administration/alternance_fiche_suivi/new.html.twig', [
            'alternance_fiche_suivi' => $alternanceFicheSuivi,
            'form'                   => $form->createView(),
        ]);
    }

    /**
     * @Route("/imprimer/{id}", name="administration_alternance_fiche_suivi_export", methods={"GET"})
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function print(
        MyAlternanceFicheSuivi $myAlternanceFicheSuivi,
        AlternanceFicheSuivi $alternanceFicheSuivi
    ): void {
        $myAlternanceFicheSuivi->print($alternanceFicheSuivi);
    }

    /**
     * @Route("/{id}", name="administration_alternance_fiche_suivi_show", methods={"GET"})
     */
    public function show(AlternanceFicheSuivi $alternanceFicheSuivi): Response
    {
        return $this->render('administration/alternance_fiche_suivi/show.html.twig', [
            'alternance_fiche_suivi' => $alternanceFicheSuivi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="administration_alternance_fiche_suivi_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AlternanceFicheSuivi $alternanceFicheSuivi): Response
    {
        $form = $this->createForm(AlternanceFicheSuiviType::class, $alternanceFicheSuivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'fiche_suivi.edit.success.flash');

            if (null !== $request->request->get('btn_update') && null !== $alternanceFicheSuivi->getAlternance()) {
                return $this->redirectToRoute('administration_alternance_show',
                    ['alternance' => $alternanceFicheSuivi->getAlternance()->getId()]);
            }

            return $this->redirectToRoute('administration_alternance_fiche_suivi_edit',
                ['id' => $alternanceFicheSuivi->getId()]);
        }

        return $this->render('administration/alternance_fiche_suivi/edit.html.twig', [
            'alternance_fiche_suivi' => $alternanceFicheSuivi,
            'form'                   => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_alternance_fiche_suivi_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AlternanceFicheSuivi $alternanceFicheSuivi): Response
    {
        $alternance = $alternanceFicheSuivi->getAlternance();

        if ($this->isCsrfTokenValid('delete' . $alternanceFicheSuivi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($alternanceFicheSuivi);
            $entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'fiche_suivi.delete.success.flash');
        }
        if (null !== $alternance) {
            return $this->redirectToRoute('administration_alternance_show', ['alternance' => $alternance->getId()]);
        }

        return $this->redirectToRoute('administration_index');
    }
}
