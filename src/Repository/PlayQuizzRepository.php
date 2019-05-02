<?php

namespace App\Repository;

use App\Entity\PlayQuizz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlayQuizz|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayQuizz|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayQuizz[]    findAll()
 * @method PlayQuizz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayQuizzRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlayQuizz::class);
    }

    // /**
    //  * @return PlayQuizz[] Returns an array of PlayQuizz objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlayQuizz
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
