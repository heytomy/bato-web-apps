<?php

namespace App\Repository;

use App\Entity\RepCommentairesChantier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RepCommentairesChantier>
 *
 * @method RepCommentairesChantier|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepCommentairesChantier|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepCommentairesChantier[]    findAll()
 * @method RepCommentairesChantier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepCommentairesChantierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepCommentairesChantier::class);
    }

    public function save(RepCommentairesChantier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RepCommentairesChantier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RepCommentairesChantier[] Returns an array of RepCommentairesChantier objects
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

//    public function findOneBySomeField($value): ?RepCommentairesChantier
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
