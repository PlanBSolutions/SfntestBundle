<?php

namespace App\Planb\SfntestBundle\Repository;

use App\Planb\SfntestBundle\Entity\StockManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockManager[]    findAll()
 * @method StockManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockManager::class);
    }
    
    /**
     * Check for dublicates in order to update rather then insert in case
     * 
     * @param StockManager $stockItem
     * @return StockManager
     */
    public function findDublicate(StockManager $stockItem)
    {
        return $this->createQueryBuilder('s')
                    ->andWhere('s.SKU = :sku')
                    ->andWhere('s.Branch = :branch')
                    ->setParameter('sku', $stockItem->getSKU())
                    ->setParameter('branch', $stockItem->getBranch())
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    // /**
    //  * @return StockManager[] Returns an array of StockManager objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StockManager
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
