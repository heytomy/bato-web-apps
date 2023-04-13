<?php

namespace App\Repository;

use App\Entity\CommentairesChantier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentairesChantier>
 *
 * @method CommentairesChantier|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentairesChantier|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentairesChantier[]    findAll()
 * @method CommentairesChantier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesChantierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentairesChantier::class);
    }

    public function save(CommentairesChantier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommentairesChantier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CommentairesChantier[] Returns an array of CommentairesChantier objects
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

//    public function findOneBySomeField($value): ?CommentairesChantier
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
