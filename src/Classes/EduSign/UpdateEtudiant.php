<?php
/*
 * Copyright (c) 2023. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Classes/EduSign/UpdateEtudiant.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 01/08/2023 15:09
 */

namespace App\Classes\EduSign;

use App\Classes\EduSign\Adapter\IntranetEtudiantEduSignAdapter;
use App\Repository\DiplomeRepository;
use App\Repository\EtudiantRepository;
use App\Repository\SemestreRepository;

class UpdateEtudiant
{
    public function __construct(
        private readonly ApiEduSign  $apiEduSign,
        protected DiplomeRepository  $diplomeRepository,
        protected SemestreRepository $semestreRepository,
        protected EtudiantRepository $etudiantRepository,
    )
    {
    }

    public function update()
    {
        $diplomesBut = $this->diplomeRepository->findAllWithEduSign();

        foreach ($diplomesBut as $diplome) {
            $semestres = $this->semestreRepository->findByDiplome($diplome);

            foreach ($semestres as $semestre) {
                $etudiants = $this->etudiantRepository->findBySemestre($semestre);

                foreach ($etudiants as $etudiant) {

                    $groupes = [];
                    foreach ($etudiant->getGroupes() as $groupe) {
                        $groupes[] = $groupe->getIdEduSign();
                    }

                    $etudiantEduSign = (new IntranetEtudiantEduSignAdapter($etudiant, $groupes))->getEtudiant();
                    if ($etudiantEduSign->id_edu_sign == null) {
                        $this->apiEduSign->addEtudiant($etudiantEduSign);
                    } else {
                        dump('etudiant déjà envoyé');
                    }
                }
            }
        }
    }
}
