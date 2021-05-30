<?php

namespace App\Repository;

use App\Entity\DeliveryForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeliveryForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryForm[]    findAll()
 * @method DeliveryForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryForm::class);
    }

    // /**
    //  * @return DeliveryForm[] Returns an array of DeliveryForm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeliveryForm
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
