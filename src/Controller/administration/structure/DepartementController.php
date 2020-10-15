<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/administration/structure/DepartementController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 15/10/2020 12:29

namespace App\Controller\administration\structure;

use App\Controller\BaseController;
use App\Entity\Constantes;
use App\Entity\Departement;
use App\Entity\Diplome;
use App\Form\DepartementType;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration/structure/departement")
 */
class DepartementController extends BaseController
{
    /**
     * @Route("/{id}", name="administration_departement_show", methods="GET")
     * @param Departement $departement
     *
     * @return Response
     */
    public function show(Departement $departement): Response
    {
        return $this->render('structure/departement/show.html.twig', ['departement' => $departement]);
    }

    /**
     * @Route("/{id}/edit", name="administration_departement_edit", methods="GET|POST")
     * @param Request     $request
     * @param Departement $departement
     *
     * @return Response
     * @throws LogicException
     */
    public function delete(Request $request, Departement $departement): Response
    {
        $id = $departement->getId();
        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token')) &&
            count($departement->getDiplomes()) === 0 &&
            count($departement->getHrs()) === 0 &&
            count($departement->getEtudiants()) === 0 &&
            count($departement->getPersonnelDepartements()) === 0 &&
            count($departement->getArticleCategories()) === 0 &&
            count($departement->getTypeDocuments()) === 0 &&
            count($departement->getCreneauCours()) === 0 &&
            count($departement->getActualites()) === 0) {
            $this->entityManager->remove($departement);
            $this->entityManager->flush();
            $this->addFlashBag(
                Constantes::FLASHBAG_SUCCESS,
                'departement.delete.success.flash'
            );

            return $this->json($id, Response::HTTP_OK);
        }

        $this->addFlashBag(Constantes::FLASHBAG_ERROR, 'departement.delete.error.flash');

        return $this->json(false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
