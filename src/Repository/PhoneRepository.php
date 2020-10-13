<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Phone find($id, $lockMode = null, $lockVersion = null)
 * @method null|Phone findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    /**
     * getPaginatedPhones.
     *
     * @param int $page  page number
     * @param int $limit number of phones per page
     *
     * @return Paginator<Phone>
     */
    public function getPaginatedPhones(int $page, int $limit): Paginator
    {
        return new Paginator(
            $this
            ->createQueryBuilder('p')
            ->addSelect('screen')
            ->leftJoin('p.screen', 'screen')
            ->addSelect('size')
            ->leftJoin('p.size', 'size')
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
        );
    }

    // /**
    //  * @return Phone[] Returns an array of Phone objects
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
    public function findOneBySomeField($value): ?Phone
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
