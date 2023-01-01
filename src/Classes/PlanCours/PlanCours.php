<?php
/*
 * Copyright (c) 2023. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/Sites/intranetV3/src/Classes/PlanCours/PlanCours.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 01/01/2023 11:52
 */

namespace App\Classes\PlanCours;

use App\Components\PlanCours\PlanCoursManager;
use App\Entity\AnneeUniversitaire;
use App\Entity\Personnel;

class PlanCours
{
    public function __construct(
        protected PlanCoursManager $planCoursManager,
    ) {
    }

    public function getPlansCoursPrevisionnel(array $previsionnels, Personnel $personnel, AnneeUniversitaire $anneeUniversitaire): array
    {
        $plCours = $this->planCoursManager->findByIntervenantsAndAnnee($personnel, $anneeUniversitaire);

        $plansCours = [];
        $plansCoursPrevisionnels = [];
        foreach ($plCours as $pcApc) {
            $plansCours[$pcApc->getTypeIdMatiere()] = $pcApc;
        }

        foreach ($previsionnels as $previsionnel) {
            if (array_key_exists($previsionnel->getTypeIdMatiere(), $plansCours)) {
                $plansCoursPrevisionnels[$previsionnel->id] = $plansCours[$previsionnel->getTypeIdMatiere()];
            }
        }

        return $plansCoursPrevisionnels;
    }
}
