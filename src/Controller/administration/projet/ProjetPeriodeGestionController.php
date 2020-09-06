<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Controller/administration/projet/ProjetPeriodeGestionController.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 06/09/2020 10:59

namespace App\Controller\administration\projet;

use App\Classes\MyExport;
use App\Classes\MyProjet;
use App\Controller\BaseController;
use App\Entity\ProjetPeriode;
use App\Entity\StagePeriode;
use App\Classes\MyStage;
use App\Repository\ProjetPeriodeRepository;
use App\Repository\StagePeriodeRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StagePeriodeGestionController
 * @package App\Controller\administration
 * @Route("/administration/projet/periode/gestion")
 *
 */
class ProjetPeriodeGestionController extends BaseController
{
    /**
     * @Route("/{uuid}/export.{_format}", name="administration_projet_periode_gestion_export", methods="GET",
     *                             requirements={"_format"="csv|xlsx|pdf"})
     * @ParamConverter("stagePeriode", options={"mapping": {"uuid": "uuid"}})
     * @param MyExport               $myExport
     * @param ProjetPeriode          $stagePprojetPeriodeeriode
     *
     * @param                        $_format
     *
     * @return Response
     * @throws Exception
     */
    public function export(MyExport $myExport, ProjetPeriode $projetPeriode, $_format): Response
    {
        $projetEtudiants = $projetPeriode->getStageEtudiants();

        return $myExport->genereFichierGenerique(
            $_format,
            $projetEtudiants,
            'periode_stage_' . $projetPeriode->getLibelle(),
            ['projet_periode_gestion', 'utilisateur', 'projet_entreprise_administration', 'adresse'],
            [
                'etudiant'            => ['nom', 'prenom'],
                'entreprise'          => ['raisonSociale', 'libelle'],
                'tuteur'              => ['nom', 'prenom', 'fonction', 'telephone', 'email'],
                'tuteurUniversitaire' => ['nom', 'prenom'],
                'dateDebutStage',
                'dateFinStage'
            ]
        );
    }

    /**
     * @Route("/{uuid}", name="administration_projet_periode_gestion")
     * @ParamConverter("projetPeriode", options={"mapping": {"uuid": "uuid"}})
     * @param StagePeriodeRepository $stagePeriodeRepository
     * @param StagePeriode           $stagePeriode
     *
     * @return Response
     */
    public function periode(
        ProjetPeriodeRepository $projetPeriodeRepository,
        MyProjet $myProjet,
        ProjetPeriode $projetPeriode
    ): Response {
        $periodes = [];
        foreach ($this->dataUserSession->getDiplomes() as $diplome) {
            $pers = $projetPeriodeRepository->findByDiplome($diplome, $diplome->getAnneeUniversitaire());
            foreach ($pers as $periode) {
                $periodes[] = $periode;
            }
        }

        return $this->render('administration/projet/projet_periode_gestion/index.html.twig', [
            'projetPeriode' => $projetPeriode,
            'periodes'      => $periodes,
            'myProjet'      => $myProjet->getDataPeriode($projetPeriode)
        ]);
    }


}
