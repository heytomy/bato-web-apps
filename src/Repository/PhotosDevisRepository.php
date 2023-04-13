<?php

namespace App\Repository;

use App\Entity\PhotosDevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhotosDevis>
 *
 * @method PhotosDevis|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotosDevis|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotosDevis[]    findAll()
 * @method PhotosDevis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotosDevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotosDevis::class);
    }

    public function save(PhotosDevis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PhotosDevis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PhotosDevis[] Returns an array of PhotosDevis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PhotosDevis
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
