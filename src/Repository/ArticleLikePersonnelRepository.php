<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\ArticleLikePersonnel;
use App\Entity\Personnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method ArticleLikePersonnel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleLikePersonnel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleLikePersonnel[]    findAll()
 * @method ArticleLikePersonnel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleLikePersonnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleLikePersonnel::class);
    }

    public function findLike(Personnel $personnel, Article $article)
    {

        return $this->createQueryBuilder('a')
            ->where('a.personnel = :personnel')
            ->andWhere('a.article = :article')
            ->setParameter('personnel', $personnel->getId())
            ->setParameter('article', $article->getId())
            ->getQuery()
            ->getResult();

    }
}
