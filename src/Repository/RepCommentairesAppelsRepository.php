<?php

namespace App\Repository;

use App\Entity\RepCommentairesAppels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RepCommentairesAppels>
 *
 * @method RepCommentairesAppels|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepCommentairesAppels|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepCommentairesAppels[]    findAll()
 * @method RepCommentairesAppels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepCommentairesAppelsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepCommentairesAppels::class);
    }

    public function save(RepCommentairesAppels $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RepCommentairesAppels $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RepCommentairesAppels[] Returns an array of RepCommentairesAppels objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RepCommentairesAppels
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
