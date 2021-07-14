<?php

namespace App\Repository;

use App\Entity\Historiquedeconnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Historiquedeconnection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Historiquedeconnection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Historiquedeconnection[]    findAll()
 * @method Historiquedeconnection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriquedeconnectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historiquedeconnection::class);
    }

    // /**
    //  * @return Historiquedeconnection[] Returns an array of Historiquedeconnection objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Historiquedeconnection
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
