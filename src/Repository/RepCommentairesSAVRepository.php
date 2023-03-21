<?php

namespace App\Repository;

use App\Entity\RepCommentairesSAV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RepCommentairesSAV>
 *
 * @method RepCommentairesSAV|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepCommentairesSAV|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepCommentairesSAV[]    findAll()
 * @method RepCommentairesSAV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepCommentairesSAVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepCommentairesSAV::class);
    }

    public function save(RepCommentairesSAV $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RepCommentairesSAV $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RepCommentairesSAV[] Returns an array of RepCommentairesSAV objects
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

//    public function findOneBySomeField($value): ?RepCommentairesSAV
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
