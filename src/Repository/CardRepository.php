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
            ->addOrderBy('c.name')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Card[] Returns an array of Card objects with the exact colors specified or less
     */
    public function findByColorsOrLess($colors): array
    {
        if ($colors=="") {
            return $this->findByExactColors($colors);
        }
        if ($colors=="BGRUW") {
            return $this->findAll();
        }

        $colorsArray = str_split($colors);
        $length = sizeof($colorsArray);
        $builder = $this->createQueryBuilder('c');

        if ($length===1) {
            $builder->orWhere('c.colors = :col')
                ->setParameter('col', $colorsArray[0]);
        }
        else if ($length===2) {
            $builder->orWhere('c.colors = :col0')
                ->setParameter('col0', $colorsArray[0])
                ->orWhere('c.colors = :col1')
                ->setParameter('col1', $colorsArray[1])
                ->orWhere('c.colors = :colDuo')
                ->setParameter('colDuo', $colorsArray[0] . $colorsArray[1]);
        }
        else if ($length===3) {
            $builder->orWhere('c.colors = :col0')
                ->setParameter('col0', $colorsArray[0])
                ->orWhere('c.colors = :col1')
                ->setParameter('col1', $colorsArray[1])
                ->orWhere('c.colors = :col2')
                ->setParameter('col2', $colorsArray[2])
                ->orWhere('c.colors = :colDuo1')
                ->setParameter('colDuo1', $colorsArray[0] . $colorsArray[1])
                ->orWhere('c.colors = :colDuo2')
                ->setParameter('colDuo2', $colorsArray[0] . $colorsArray[2])
                ->orWhere('c.colors = :colDuo3')
                ->setParameter('colDuo3', $colorsArray[1] . $colorsArray[2])
                ->orWhere('c.colors = :colTrio')
                ->setParameter('colTrio', $colorsArray[0] . $colorsArray[1] . $colorsArray[2]);
        }
        else if ($length===4) {
            $builder->orWhere('c.colors = :col0')
                ->setParameter('col0', $colorsArray[0])
                ->orWhere('c.colors = :col1')
                ->setParameter('col1', $colorsArray[1])
                ->orWhere('c.colors = :col2')
                ->setParameter('col2', $colorsArray[2])
                ->orWhere('c.colors = :col3')
                ->setParameter('col3', $colorsArray[3])
                ->orWhere('c.colors = :colDuo1')
                ->setParameter('colDuo1', $colorsArray[0] . $colorsArray[1])
                ->orWhere('c.colors = :colDuo2')
                ->setParameter('colDuo2', $colorsArray[0] . $colorsArray[2])
                ->orWhere('c.colors = :colDuo3')
                ->setParameter('colDuo3', $colorsArray[0] . $colorsArray[3])
                ->orWhere('c.colors = :colDuo4')
                ->setParameter('colDuo4', $colorsArray[1] . $colorsArray[2])
                ->orWhere('c.colors = :colDuo5')
                ->setParameter('colDuo5', $colorsArray[1] . $colorsArray[3])
                ->orWhere('c.colors = :colDuo6')
                ->setParameter('colDuo6', $colorsArray[2] . $colorsArray[3])
                ->orWhere('c.colors = :colTrio1')
                ->setParameter('colTrio1', $colorsArray[0] . $colorsArray[1] . $colorsArray[2])
                ->orWhere('c.colors = :colTrio2')
                ->setParameter('colTrio2', $colorsArray[0] . $colorsArray[1] . $colorsArray[3])
                ->orWhere('c.colors = :colTrio3')
                ->setParameter('colTrio3', $colorsArray[0] . $colorsArray[2] . $colorsArray[3])
                ->orWhere('c.colors = :colTrio4')
                ->setParameter('colTrio4', $colorsArray[1] . $colorsArray[2] . $colorsArray[3]);
        }

        $builder->orWhere('c.colors = :colorless')
            ->setParameter('colorless', "");

        return $builder
            ->addOrderBy('c.name')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Card[] Returns an array of Card objects with the exact colors specified or more
     */
    public function findByColorsOrMore($colors): array
    {
        if ($colors == "") {
            return $this->findAll();
        }
        if ($colors == "BGRUW") {
            return $this->findByExactColors($colors);
        }

        $colorsArray = str_split($colors);
        $queryColorArray = [];
        foreach($colorsArray as $color) {
            $queryColorArray[] = "%" . $color . "%";
        }
        $queryColorArray = implode($queryColorArray);
        $builder = $this->createQueryBuilder('c');
        $builder->orWhere('c.colors LIKE :col')
            ->setParameter('col', $queryColorArray);

        return $builder
            ->addOrderBy('c.name')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
    * @return Card[] Returns an array of Card objects
    */
    public function findByName($search) : array
    {
        return $builder = $this->createQueryBuilder('card')
            ->andWhere('card.name LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()->getResult();
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
