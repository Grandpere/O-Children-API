<?php

namespace App\Repository;

use App\Entity\World;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method World|null find($id, $lockMode = null, $lockVersion = null)
 * @method World|null findOneBy(array $criteria, array $orderBy = null)
 * @method World[]    findAll()
 * @method World[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorldRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, World::class);
    }

    // /**
    //  * @return World[] Returns an array of World objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?World
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function allQuizzs($world)
    {
        $query = $this->createQueryBuilder('w')
                        ->innerJoin('w.quizzs', 'q')
                        ->addSelect('q')
                        ->andWhere('w.id = :world')
                        ->setParameter('world', $world)
                        ->orderBy('q.id', 'ASC');
        return $query->getQuery()->getResult();
    }
}
