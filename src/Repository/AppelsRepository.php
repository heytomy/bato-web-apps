<?php

namespace App\Repository;

use App\Entity\Appels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appels>
 *
 * @method Appels|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appels|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appels[]    findAll()
 * @method Appels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppelsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appels::class);
    }

    public function save(Appels $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Appels $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param string $stat le paramètre du statut en chaîne de charactère
     * @return Appels[] Renvoie une liste des Appels d'un statut particulier
     */
    public function findByStatut(string $stat)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.statut', 's')
            ->andWhere('s.statut = :stat')
            ->orderBy('a.id', 'DESC')
            ->setParameter('stat', $stat)
            ->getQuery()
            ->getResult()
            ;
    }

    

//    /**
//     * @return Appels[] Returns an array of Appels objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Appels
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
