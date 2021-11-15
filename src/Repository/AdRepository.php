<?php

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    // /**
    //  * @return Ad[] Returns an array of Ad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Ad[]
     */
    public function findAdBySelection($brand, $model, $fuel, $kilometers, $year, $price){
        $qb = $this->createQueryBuilder('a')
            ->Where('YEAR(a.year) BETWEEN :yearMin AND :yearMax')
            ->andWhere('a.kilometers BETWEEN :kilometersMin AND :kilometersMax')
            ->andWhere('a.price BETWEEN :priceMin AND :priceMax')
        ->setParameters(array('yearMin' => $year[0],
            'yearMax'=>$year[1],
            'kilometersMin' => $kilometers[0],
            'kilometersMax'=> $kilometers[1],
            'priceMin' => $price[0],
            'priceMax' => $price[1]
            ));

        if(!empty($model))
        {
            $qb->andWhere('a.model = :model')
            ->setParameter('model', $model);
        }
        if($brand !== null)
        {
            $qb->leftJoin('a.model', "m")
            ->andWhere('m.brand = :brand')
          ->setParameter('brand', $brand);
        }
        if(!empty($fuel))
        {
            $qb->andWhere('a.fuel = :fuel')
          ->setParameter('fuel', $fuel);
        }
        return $qb->orderBy('a.id', 'DESC')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult();

    }

}
