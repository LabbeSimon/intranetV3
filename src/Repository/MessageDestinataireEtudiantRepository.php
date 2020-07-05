<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Repository/MessageDestinataireEtudiantRepository.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 05/07/2020 08:13

namespace App\Repository;

use App\Entity\Etudiant;
use App\Entity\Message;
use App\Entity\MessageDestinataireEtudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method MessageDestinataireEtudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageDestinataireEtudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageDestinataireEtudiant[]    findAll()
 * @method MessageDestinataireEtudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageDestinataireEtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageDestinataireEtudiant::class);
    }

    public function findLast(Etudiant $user, $nbMessage = 0, $filtre = '')
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.etudiant = :etudiant')
            ->setParameter('etudiant', $user->getId())
            ->orderBy('m.created', 'DESC');

        switch ($filtre){
            case 'all':
                $query->andWhere('m.etat = :read or m.etat = :unread')
                    ->setParameter('read', 'R')
                    ->setParameter('unread', 'U');
                break;
            case 'trash':
                $query->andWhere('m.etat = :delete')
                    ->setParameter('delete', 'D');
                break;
            case 'starred':
                $query->andWhere('m.starred = 1');
                break;
        }

        if ($nbMessage > 0) {
            $query->setMaxResults($nbMessage);
        }

        return $query->getQuery()
            ->getResult();
    }

    /**
     * @param $user
     * @param $message
     *
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findDest(Etudiant $user, Message $message): ?MessageDestinataireEtudiant
    {
        return $this->createQueryBuilder('m')
            ->where('m.etudiant = :etudiant')
            ->andWhere('m.message = :message')
            ->setParameter('etudiant', $user->getId())
            ->setParameter('message', $message->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Etudiant $user
     *
     * @return mixed
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getNbUnread(Etudiant $user)
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.etudiant = :etudiant')
            ->setParameter('etudiant', $user->getId())
            ->andWhere('m.etat = :unread')
            ->setParameter('unread', 'U')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
