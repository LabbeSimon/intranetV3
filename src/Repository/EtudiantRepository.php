<?php

namespace App\Repository;

use App\Entity\Annee;
use App\Entity\Diplome;
use App\Entity\Etudiant;
use App\Entity\Semestre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Etudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiant[]    findAll()
 * @method Etudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantRepository extends ServiceEntityRepository
{
    /**
     * EtudiantRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    /**
     * @param $getId
     * @param $filters
     * @param $start
     * @param $length
     *
     * @return array
     */
    public function getArrayEtudiantsByDepartement($getId, $filters, $start, $length): array
    {
        $etudiants = $this->getByDepartement($getId, $filters, $start, $length);
        $tab = [];
        /** @var Etudiant $etudiant */
        foreach ($etudiants as $etudiant) {
            $t = [];

            $t['numetudiant'] = $etudiant->getId();
            $t['nom'] = $etudiant->getNom();
            $t['prenom'] = $etudiant->getPrenom();
            $t['semestre'] = $etudiant->getSemestre()->getLibelle();
            $t['profil'] = '';

            $tab[] = $t;
        }

        return $tab;
    }

    /**
     * @param      $departement
     * @param      $data
     * @param int  $page
     * @param null $max
     * @param bool $getResult
     *
     * @return \Doctrine\ORM\Query|mixed
     */
    public function getByDepartement($departement, $data, $page = 0, $max = null, $getResult = true)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = isset($data['query']) && $data['query'] ? $data['query'] : null;

        $qb
            ->select('u')
            ->from(Etudiant::class, 'u')
            ->innerJoin(Semestre::class, 's', 'WITH', 'u.semestre = s.id')
            ->innerJoin(Annee::class, 'a', 'WITH', 's.annee = a.id')
            ->innerJoin(Diplome::class, 'd', 'WITH', 'a.diplome = d.id')
            ->where('d.departement = :departement')
            // ->andWhere('u.visible = :visible')
            // ->andWhere('u.anneesortie = :anneesortie')
            ->setParameters(['departement' => $departement]);

        /*switch ($order[0]['column']) {
            case 0:
                //todo: mettre les tris.
                break;
        }*/

        if ($query) {
            $qb
                ->andWhere('u.nom like :query')
                ->orWhere('u.prenom like :query')
                ->setParameter('query', '%' . $query . '%');
        }

        if ($max) {
            $preparedQuery = $qb->getQuery()
                ->setMaxResults($max)
                ->setFirstResult($page * $max);
        } else {
            $preparedQuery = $qb->getQuery();
        }

        return $getResult ? $preparedQuery->getResult() : $preparedQuery;
    }

    /**
     * @param $semestre
     *
     * @return array
     */
    public function findBySemestre($semestre): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.semestre = :semestre')
            ->setParameter('semestre', $semestre)
            ->orderBy('e.nom', 'ASC')
            ->orderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $slug
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySlug($slug)
    {
        return $this->createQueryBuilder('e')
            ->where('e.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $needle
     *
     * @return array
     */
    public function search($needle): array
    {
        $query = $this->searchObject($needle);

        $t = [];

        /** @var Etudiant $etudiant */
        foreach ($query as $etudiant) {
            $tt = [];
            $tt['displayPr'] = $etudiant->getDisplayPr();
            $tt['slug'] = $etudiant->getSlug();
            $tt['photo'] = $etudiant->getPhotoName();
            $t[] = $tt;
        }

        return $t;
    }

    /**
     * @param $uuid
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUuid($uuid)
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.uuid = :uuid')
            ->setParameter('uuid', $uuid);

        return $query->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Annee $annee
     *
     * @return mixed
     */
    public function findByAnnee(Annee $annee)
    {
        $query = $this->createQueryBuilder('e');
        $i = 1;
        foreach ($annee->getSemestres() as $semestre) {
            $query->orWhere('e.semestre = ?' . $i)
                ->setParameter($i, $semestre->getId());
            $i++;
        }

        return $query->orderBy('e.nom', 'ASC')
            ->orderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchObject($needle)
    {
        return $this->createQueryBuilder('p')
            ->where('p.nom LIKE :needle')
            ->orWhere('p.prenom LIKE :needle')
            ->orWhere('p.username LIKE :needle')
            ->orWhere('p.mailUniv LIKE :needle')
            ->orWhere('p.numEtudiant LIKE :needle')
            ->orWhere('p.numIne LIKE :needle')
            ->setParameter('needle', '%' . $needle . '%')
            ->orderBy('p.nom', 'ASC')
            ->orderBy('p.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
