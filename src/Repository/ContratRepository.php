<?php

namespace App\Repository;

use App\Entity\Contrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contrat>
 *
 * @method Contrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrat[]    findAll()
 * @method Contrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrat::class);
    }

    public function save(Contrat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contrat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function getCountClients()
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select(
            $qb->expr()->count(x: 'c.id')
        )
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByLimit(?int $offset = 0, int $limit = 10)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select()
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        return $qb->getQuery()->getResult();
    }

    public function findByLimitArray(?int $offset = 0, int $limit = 10)
    {
        $clients = $this->findByLimit($offset, $limit);

        $data = [];

        foreach ($clients as $key => $client) {
            $data[] = [
                'codeContrat'           =>  $client->getId(),
                'note'                  =>  $client->getNote(),
                'codeClient'            =>  $client->getCodeClient()->getId(),
                'nom'                   =>  $client->getCodeClient()->getNom(),
                'adr'                   =>  $client->getCodeClient()->getAdr(),
                'cp'                    =>  $client->getCodeClient()->getCP(),
                'ville'                 =>  $client->getCodeClient()->getVille(),
                'tel'                   =>  $client->getCodeClient()->getTel(),
                'email'                 =>  $client->getCodeClient()->getEMail(),
            ];
        }
        
        return $data;
    }
//    /**
//     * @return Contrat[] Returns an array of Contrat objects
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

//    public function findOneBySomeField($value): ?Contrat
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
