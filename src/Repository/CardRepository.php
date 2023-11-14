<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 *
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    /**
     * @return Card[] Returns an array of Card objects with the exact colors specified
     */
    public function findByExactColors($colors): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.colors = :col')
            ->setParameter('col', $colors)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Card[] Returns an array of Card objects with the exact colors specified or less
     */
    public function findByColorsOrLess($colors): array
    {
        if ($colors=="C") {
            return $this->findByExactColors($colors);
        }
        if ($colors=="BGRUW") {
            return $this->findAll();
        }

        $colorsArray = str_split($colors);
        $builder = $this->createQueryBuilder('c');

        $index = 0;
        foreach ($colorsArray as $color) {
            $builder->orWhere('c.colors LIKE :col' . $index)
                ->setParameter('col' . $index, "%" . $color . "%");
            $index++;
        }

        $builder->orWhere('c.colors = :colorless')
            ->setParameter('colorless', "C");

        return $builder
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Card[] Returns an array of Card objects with the exact colors specified or more
     */
    public function findByColorsOrMore($colors): array
    {
        if ($colors == "C") {
            return $this->findAll();
        }
        if ($colors == "BGRUW") {
            return $this->findByExactColors($colors);
        }

        $colorsArray = str_split($colors);
        $builder = $this->createQueryBuilder('c');
        $builder->orWhere('c.colors LIKE :col')
            ->setParameter('col', "%" . $colors . "%");

//        $index = 0;
//        foreach ($colorsArray as $color) {
//            $builder->orWhere('c.colors LIKE :col' . $index)
//                ->setParameter('col' . $index, "%" . $color . "%");
//            $index++;
//        }

        return $builder
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Card[] Returns an array of every Card object in the database
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('c')->getQuery()->getResult();
    }

//    /**
//     * @return Card[] Returns an array of Card objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Card
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
