<?php

namespace App\Repository;

use App\Classe\ProductFilter;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
    * @return Product[] Returns an array of Product objects/    
    */
    public function findWithSearch($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :val')
            ->setParameter('val', "%{$value}%")
            ->getQuery()
            ->getResult()
        ;
    }

       /**
     * @return Product[] Returns an array of Product objects
     */
    public function findWithFilter(ProductFilter $productFilter): array
    {
        $values = [
            'min1' => 0,
            'max1'=>1000,
            'min2'=>1000,
            'max2'=>5000,
            'min3'=>5000,
            'max3'=>10000000,
            ];
        if ($productFilter->getPrices()) {
            $values = [
                'min1' => 0,
                'max1'=>0,
                'min2'=>0,
                'max2'=>0,
                'min3'=>0,
                'max3'=>0,
                ];
            foreach ($productFilter->getPrices() as  $val) {
                if ($val == 1) {
                    $values['max1'] = 1000;
                }
                if($val == 2) {
                    $values['min2'] = 1000;
                    $values['max2'] = 5000;
                }
                if($val == 3) {
                    $values['min3'] = 5000;
                    $values['max3'] = 10000000;
                }
            }
        }
        return $this->createQueryBuilder('p')
            ->orWhere('p.price BETWEEN :min1 AND :max1')
            ->orWhere('p.price BETWEEN :min2 AND :max2')
            ->orWhere('p.price BETWEEN :min3 AND :max3')
            ->setParameter('min1', 0)
            ->setParameter('max1', $values['max1'])
            ->setParameter('min2', $values['min2'])
            ->setParameter('max2', $values['max2'])
            ->setParameter('min3', $values['min3'])
            ->setParameter('max3', $values['max3'])
           ->getQuery()
           ->getResult()
        ;
    }
}
