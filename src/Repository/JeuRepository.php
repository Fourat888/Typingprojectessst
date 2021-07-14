<?php

namespace App\Repository;

use App\Entity\Jeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Jeu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jeu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jeu[]    findAll()
 * @method Jeu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JeuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jeu::class);
    }

    public function nbjeux($lvl,$type){
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT MAX(j.score) FROM APP\Entity\Jeu j where j.level=:level and j.partietype=:ptype')
        ->setParameter('level',$lvl)
        ->setParameter('ptype',$type);
return $query->getSingleScalarResult();
    }
    public function test($lvl,$type)
    {
        $query = $this->createQueryBuilder('j');
        $query->select('max(j.score) AS max_score');
        $query->where(' j.level=:level')      ->setParameter('level',$lvl);
        $query->andWhere(' j.partietype=:ptype')        ->setParameter('ptype',$type);
        return $query->getQuery()->getSingleScalarResult();
    }
    public function test3($temps,$tempsmax)
    {
        $query = $this->createQueryBuilder('j');
        $query->select('count(j.temps) AS temps ,count (j.tempsmax) as tempsmax');
        $query->where(' j.temps=:temps')      ->setParameter('temps',$temps);
        $query->andWhere(' j.tempsmax=:tempsmax')        ->setParameter('tempsmax',$tempsmax);
        return $query->getQuery()->getSingleScalarResult();
    }
    public function test2()
    {
        $query = $this->createQueryBuilder('j');
        $query->select('max(j.score) AS max_score');

        return $query->getQuery()->getSingleScalarResult();
    }
    // /**
    //  * @return Jeu[] Returns an array of Jeu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Jeu
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
