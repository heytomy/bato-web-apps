<?php

namespace App\Repository;

use App\Entity\DefAppsUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DefAppsUtilisateur>
 *
 * @method DefAppsUtilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method DefAppsUtilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method DefAppsUtilisateur[]    findAll()
 * @method DefAppsUtilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DefAppsUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DefAppsUtilisateur::class);
    }

    public function save(DefAppsUtilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DefAppsUtilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findByRoleTech(string $role): array
    {
        $queryBuilder = $this->createQueryBuilder('d');
        $queryBuilder->innerJoin('d.comptes', 'a')
            ->innerJoin('a.roles', 'r')
            ->where('r.Libelle = :role')
            ->setParameter('role', $role);
    
        return $queryBuilder->getQuery()->getResult();
    }
    

//    /**
//     * @return DefAppsUtilisateur[] Returns an array of DefAppsUtilisateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DefAppsUtilisateur
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
