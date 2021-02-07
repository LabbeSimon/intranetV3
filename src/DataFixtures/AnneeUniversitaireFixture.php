<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/DataFixtures/AnneeUniversitaireFixture.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 07/02/2021 10:40
 */

namespace App\DataFixtures;

use App\Entity\AnneeUniversitaire;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnneeUniversitaireFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $td = new AnneeUniversitaire();
        $td->setLibelle('2019-2020');
        $td->setActive(true);
        $td->setAnnee(2019);
        $td->setCreated(new DateTime('now'));
        $td->setUpdated(new DateTime('now'));
        $manager->persist($td);

        $manager->flush();
    }
}
