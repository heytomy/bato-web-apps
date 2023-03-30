<?php

namespace App\Repository;

use App\Entity\ClientDef;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientDef>
 *
 * @method ClientDef|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientDef|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientDef[]    findAll()
 * @method ClientDef[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientDefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientDef::class);
    }

    public function save(ClientDef $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClientDef $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findByClientWithContrats(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.contrats', 'contrat')
            ->andWhere('contrat.id is not null')
            ->orderBy('contrat.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

//    /**
//     * @return ClientDef[] Returns an array of ClientDef objects
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

//    public function findOneBySomeField($value): ?ClientDef
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
