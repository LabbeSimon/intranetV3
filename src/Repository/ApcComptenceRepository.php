<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Repository/ApcComptenceRepository.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 13/05/2021 16:47
 */

namespace App\Repository;

use App\Entity\ApcCompetence;
use App\Entity\Diplome;
use App\Entity\Ppn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApcCompetence|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApcCompetence|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApcCompetence[]    findAll()
 * @method ApcCompetence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<ApcCompetence>
 */
class ApcComptenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApcCompetence::class);
    }

    /**
     * @return ApcCompetence[]
     */
    public function findByDiplome(Diplome $diplome): array
    {
        return $this->findByDiplomeBuilder($diplome)
            ->getQuery()
            ->getResult();
    }

    public function findByDiplomeBuilder(Diplome $diplome): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.diplome = :diplome')
            ->setParameter('diplome', $diplome->getId());
    }

    /**
     * @return ApcCompetence[]
     */
    public function findByDiplomeAndPn(Diplome $diplome, Ppn $pn): array
    {
        return $this->findByDiplomeAndPnBuilder($diplome, $pn)
            ->getQuery()
            ->getResult();
    }

    public function findByDiplomeAndPnBuilder(Diplome $diplome, Ppn $pn): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.diplome = :diplome')
            ->andWhere('c.ppn = :pn')
            ->setParameter('diplome', $diplome->getId())
            ->setParameter('pn', $pn->getId());
    }

    /**
     * @return ApcCompetence[]
     */
    public function findOneByDiplomeAndPnArray(Diplome $diplome, Ppn $pn): array
    {
        $comps = $this->findByDiplomeAndPn($diplome, $pn);
        $t = [];
        foreach ($comps as $c) {
            $t[$c->getNomCourt()] = $c;
        }

        return $t;
    }
}
