<?php

namespace App\Repository;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function allQuizzs($category)
    {
        $query = $this->createQueryBuilder('c')
                        ->innerJoin('c.quizzs', 'q')
                        ->addSelect('q')
                        ->andWhere('c.id = :category')
                        ->setParameter('category', $category)
                        ->orderBy('q.id', 'ASC');
        return $query->getQuery()->getResult();
    }

    public function allPuzzles($category)
    {
        $query = $this->createQueryBuilder('c')
                        ->innerJoin('c.puzzles', 'p')
                        ->addSelect('p')
                        ->andWhere('c.id = :category')
                        ->setParameter('category', $category)
                        ->orderBy('p.id', 'ASC');
        return $query->getQuery()->getResult();
    }
}
