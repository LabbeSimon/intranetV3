<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/appPersonnel/CovidAttestationPersonnelController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 06/11/2020 15:33

namespace App\Controller\appPersonnel;

use App\Controller\BaseController;
use App\Entity\Constantes;
use App\Entity\CovidAttestationPersonnel;
use App\Event\CovidEvent;
use App\Form\CovidAttestationPersonnelType;
use App\Repository\CovidAttestationPersonnelRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/covid/attestation/personnel", name="application_personnel_")
 */
class CovidAttestationPersonnelController extends BaseController
{
    /**
     * @Route("/", name="covid_attestation_personnel_index", methods={"GET"})
     * @param CovidAttestationPersonnelRepository $covidAttestationPersonnelRepository
     *
     * @return Response
     */
    public function index(CovidAttestationPersonnelRepository $covidAttestationPersonnelRepository): Response
    {
        return $this->render('appPersonnel/covid_attestation_personnel/index.html.twig', [
            'covid_attestation_personnels' => $covidAttestationPersonnelRepository->findByPersonnel($this->getConnectedUser()),
        ]);
    }

    /**
     * @Route("/new", name="covid_attestation_personnel_new", methods={"GET","POST"})
     * @param Request                  $request
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return Response
     */
    public function new(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $covidAttestationPersonnel = new CovidAttestationPersonnel($this->getConnectedUser());
        $form = $this->createForm(CovidAttestationPersonnelType::class, $covidAttestationPersonnel,
            [
                'departement' => $this->getDepartement(),
                'attr'        => [
                    'data-provide' => 'validation'
                ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($covidAttestationPersonnel);
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'covid_attestation_personnel.add.success.flash');
            $event = new CovidEvent($covidAttestationPersonnel);
            $eventDispatcher->dispatch($event, CovidEvent::COVID_AUTORISATION_DEPOSEE);

            return $this->redirectToRoute('application_personnel_covid_attestation_personnel_index');
        }

        return $this->render('appPersonnel/covid_attestation_personnel/new.html.twig', [
            'covid_attestation_personnel' => $covidAttestationPersonnel,
            'form'                        => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="covid_attestation_personnel_show", methods={"GET"})
     * @param CovidAttestationPersonnel $covidAttestationPersonnel
     *
     * @return Response
     */
    public function show(CovidAttestationPersonnel $covidAttestationPersonnel): Response
    {
        if ($covidAttestationPersonnel->getPersonnel()->getId() === $this->getConnectedUser()->getId()) {
            return $this->render('appPersonnel/covid_attestation_personnel/show.html.twig', [
                'covid_attestation_personnel' => $covidAttestationPersonnel,
            ]);
        }

        return $this->redirectToRoute('erreur_666');
    }

    /**
     * @Route("/{id}/pdf", name="covid_attestation_personnel_pdf", methods={"GET"})
     * @param CovidAttestationPersonnel $covidAttestationPersonnel
     *
     * @return Response
     */
    public function pdf(CovidAttestationPersonnel $covidAttestationPersonnel): Response
    {
        if ($covidAttestationPersonnel->getPersonnel()->getId() === $this->getConnectedUser()->getId()) {
            $html = $this->renderView('pdf/covid/autorisationPersonnel.html.twig', [
                'covidAttestationPersonnel' => $covidAttestationPersonnel
            ]);

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isPhpEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->render();

            return new Response($dompdf->stream('attestation-' . $covidAttestationPersonnel->getPersonnel()->getNom(),
                ['Attachment' => 1]));
        }

        return $this->redirectToRoute('erreur_666');
    }

    /**
     * @Route("/{id}/edit", name="covid_attestation_personnel_edit", methods={"GET","POST"})
     * @param Request                   $request
     * @param EventDispatcherInterface  $eventDispatcher
     * @param CovidAttestationPersonnel $covidAttestationPersonnel
     *
     * @return Response
     */
    public function edit(
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        CovidAttestationPersonnel $covidAttestationPersonnel
    ): Response {
        if ($covidAttestationPersonnel->getPersonnel()->getId() === $this->getConnectedUser()->getId()) {
            $form = $this->createForm(CovidAttestationPersonnelType::class, $covidAttestationPersonnel, [
                'departement' => $this->getDepartement(),
                'attr'        => [
                    'data-provide' => 'validation'
                ]
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $covidAttestationPersonnel->setValidationDepartement(null);
                $this->entityManager->flush();
                $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'covid_attestation_personnel.edit.success.flash');

                $event = new CovidEvent($covidAttestationPersonnel);
                $eventDispatcher->dispatch($event, CovidEvent::COVID_AUTORISATION_EDITEE);

                return $this->redirectToRoute('application_personnel_covid_attestation_personnel_index');
            }

            return $this->render('appPersonnel/covid_attestation_personnel/edit.html.twig', [
                'covid_attestation_personnel' => $covidAttestationPersonnel,
                'form'                        => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('erreur_666');
    }

    /**
     * @Route("/{id}", name="covid_attestation_personnel_delete", methods="DELETE")
     * @param Request                   $request
     * @param CovidAttestationPersonnel $covidAttestationPersonnel
     *
     * @return Response
     */
    public function delete(Request $request, CovidAttestationPersonnel $covidAttestationPersonnel): Response
    {
        $id = $covidAttestationPersonnel->getId();
        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $this->entityManager->remove($covidAttestationPersonnel);
            $this->entityManager->flush();
            $this->addFlashBag(
                Constantes::FLASHBAG_SUCCESS,
                'covid_attestation_personnel.delete.success.flash'
            );

            return $this->json($id, Response::HTTP_OK);
        }

        $this->addFlashBag(Constantes::FLASHBAG_ERROR, 'covid_attestation_personnel.delete.error.flash');

        return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/{id}/duplicate", name="covid_attestation_personnel_duplicate", methods="GET|POST")
     * @param CovidAttestationPersonnel $covidAttestationPersonnel
     *
     * @return Response
     */
    public function duplicate(CovidAttestationPersonnel $covidAttestationPersonnel): Response
    {
        if ($covidAttestationPersonnel->getPersonnel()->getId() === $this->getConnectedUser()->getId()) {

            $newCovidAttestationPersonnel = clone $covidAttestationPersonnel;

            $this->entityManager->persist($newCovidAttestationPersonnel);
            $this->entityManager->flush();
            $this->addFlashBag(Constantes::FLASHBAG_SUCCESS, 'covid_attestation_personnel.duplicate.success.flash');

            return $this->redirectToRoute('application_personnel_covid_attestation_personnel_edit',
                ['id' => $newCovidAttestationPersonnel->getId()]);
        }

        return $this->redirectToRoute('erreur_666');
    }

}
