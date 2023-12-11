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

    public function findBySetAndExactColors($setName, $colors): array
    {
        return $this->createQueryBuilder("card")
            ->innerJoin("card.lastSet", "lastSet")
            ->where("lastSet.name = :lastSetName")
            ->setParameter("lastSetName", $setName)
            ->andWhere("card.colors = :colors")
            ->setParameter("colors", $colors)
            ->getQuery()
            ->getResult();
    }

    /**
    * @return Card[] Returns an array of Card objects
    */
    public function findByName(string $search) : array
    {
        return $this->createQueryBuilder('card')
            ->andWhere('card.name LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()->getResult();
    }

    /**
     * @return Card[] Returns an array of Card objects
     */
    public function findAllFiltered(
        ?string $colorTypes, ?int $colorMode,
        ?string $typeLine, ?Set $set,
        ?string $effectText, ?string $flavorText,
        ?string $statType, ?string $statOperator, ?float $statValue,
        ?string $rarity, ?string $artist) : array
    {
        $qb = $this->createQueryBuilder('card');
        switch ($colorMode){
            case 1:
                $qb
                    ->andWhere('card.colors = :col')
                    ->setParameter('col', $colorTypes);
                break;
            case 2:
                if ($colorTypes != "") {
                    if ($colorTypes == "BGRUW") {
                        $qb
                            ->andWhere('card.colors = :col')
                            ->setParameter('col', $colorTypes);
                    } else {
                        $colorsArray = str_split($colorTypes);
                        $queryColorArray = [];
                        foreach($colorsArray as $color) {
                            $queryColorArray[] = "%" . $color . "%";
                        }
                        $queryColorArray = implode($queryColorArray);
                        $qb
                            ->orWhere('card.colors LIKE :col')
                            ->setParameter('col', $queryColorArray);
                    }
                }
                break;
            case 3:
                if ($colorTypes!="BGRUW") {
                    if ($colorTypes=="") {
                        $qb
                            ->andWhere('card.colors = :col')
                            ->setParameter('col', $colorTypes);
                    }
                    $colorsArray = str_split($colorTypes);
                    $length = sizeof($colorsArray);

                    if ($length===1) {
                        $qb
                            ->orWhere('card.colors = :col')
                            ->setParameter('col', $colorsArray[0]);
                    }
                    else if ($length===2) {
                        $qb
                            ->orWhere('card.colors = :col0')
                            ->setParameter('col0', $colorsArray[0])
                            ->orWhere('card.colors = :col1')
                            ->setParameter('col1', $colorsArray[1])
                            ->orWhere('card.colors = :colDuo')
                            ->setParameter('colDuo', $colorsArray[0] . $colorsArray[1]);
                    }
                    else if ($length===3) {
                        $qb
                            ->orWhere('card.colors = :col0')
                            ->setParameter('col0', $colorsArray[0])
                            ->orWhere('card.colors = :col1')
                            ->setParameter('col1', $colorsArray[1])
                            ->orWhere('card.colors = :col2')
                            ->setParameter('col2', $colorsArray[2])
                            ->orWhere('card.colors = :colDuo1')
                            ->setParameter('colDuo1', $colorsArray[0] . $colorsArray[1])
                            ->orWhere('card.colors = :colDuo2')
                            ->setParameter('colDuo2', $colorsArray[0] . $colorsArray[2])
                            ->orWhere('card.colors = :colDuo3')
                            ->setParameter('colDuo3', $colorsArray[1] . $colorsArray[2])
                            ->orWhere('card.colors = :colTrio')
                            ->setParameter('colTrio', $colorsArray[0] . $colorsArray[1] . $colorsArray[2]);
                    }
                    else if ($length===4) {
                        $qb
                            ->orWhere('card.colors = :col0')
                            ->setParameter('col0', $colorsArray[0])
                            ->orWhere('card.colors = :col1')
                            ->setParameter('col1', $colorsArray[1])
                            ->orWhere('card.colors = :col2')
                            ->setParameter('col2', $colorsArray[2])
                            ->orWhere('card.colors = :col3')
                            ->setParameter('col3', $colorsArray[3])
                            ->orWhere('card.colors = :colDuo1')
                            ->setParameter('colDuo1', $colorsArray[0] . $colorsArray[1])
                            ->orWhere('card.colors = :colDuo2')
                            ->setParameter('colDuo2', $colorsArray[0] . $colorsArray[2])
                            ->orWhere('card.colors = :colDuo3')
                            ->setParameter('colDuo3', $colorsArray[0] . $colorsArray[3])
                            ->orWhere('card.colors = :colDuo4')
                            ->setParameter('colDuo4', $colorsArray[1] . $colorsArray[2])
                            ->orWhere('card.colors = :colDuo5')
                            ->setParameter('colDuo5', $colorsArray[1] . $colorsArray[3])
                            ->orWhere('card.colors = :colDuo6')
                            ->setParameter('colDuo6', $colorsArray[2] . $colorsArray[3])
                            ->orWhere('card.colors = :colTrio1')
                            ->setParameter('colTrio1', $colorsArray[0] . $colorsArray[1] . $colorsArray[2])
                            ->orWhere('card.colors = :colTrio2')
                            ->setParameter('colTrio2', $colorsArray[0] . $colorsArray[1] . $colorsArray[3])
                            ->orWhere('card.colors = :colTrio3')
                            ->setParameter('colTrio3', $colorsArray[0] . $colorsArray[2] . $colorsArray[3])
                            ->orWhere('card.colors = :colTrio4')
                            ->setParameter('colTrio4', $colorsArray[1] . $colorsArray[2] . $colorsArray[3]);
                    }

                    $qb
                        ->orWhere('card.colors = :colorless')
                        ->setParameter('colorless', "");
                }
                break;
            default:
                break;
        }
        if ($typeLine != null){
            $qb
                ->andWhere('card.typeline LIKE :tl')
                ->setParameter('tl', "%" . $typeLine . "%");
        }
        if ($set != null){
            $qb
                ->andWhere('card.lastSet = :set')
                ->setParameter('set', $set);
        }
        if ($effectText != null){
            $qb
                ->andWhere('card.effectText LIKE :et')
                ->setParameter('et', "%" . $effectText . "%");
        }
        if ($flavorText != null){
            $qb
                ->andWhere('card.flavorText LIKE :ft')
                ->setParameter('ft', "%" . $flavorText . "%");
        }
        if ($statType != null){
            switch ($statType){
                case 'M':
                    $qb->andWhere('card.manaValue ' . $statOperator . ' :value');
                    break;
                case 'P':
                    $qb->andWhere('card.power ' . $statOperator . ' :value');
                    break;
                case 'L':
                    $qb->andWhere('card.loyalty ' . $statOperator . ' :value');
                    break;
                case 'T':
                    $qb->andWhere('card.toughness ' . $statOperator . ' :value');
                    break;
                case 'D':
                    $qb->andWhere('card.defense ' . $statOperator . ' :value');
                    break;
            }
            $qb->setParameter('value',$statValue);
        }
        if ($rarity != null){
            $qb
                ->andWhere('card.rarity = :r')
                ->setParameter('r', $rarity);
        }
        if ($artist != null){
            $qb
                ->andWhere('card.artist LIKE :a')
                ->setParameter('a', "%" . $artist . "%");
        }
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findCardById($id): array
    {
        return $this->createQueryBuilder("card")
            ->where("card.id = :cardId")
            ->setParameter("cardId", $id)
            ->getQuery()
            ->getResult();
    }

    public function findCardByName($name): array
    {
        return $this->createQueryBuilder("card")
            ->where("card.name = :cardName")
            ->setParameter("cardName", $name)
            ->getQuery()
            ->getResult();
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
