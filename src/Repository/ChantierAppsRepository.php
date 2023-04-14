<?php

namespace App\Repository;

use App\Entity\ChantierApps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChantierApps>
 *
 * @method ChantierApps|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChantierApps|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChantierApps[]    findAll()
 * @method ChantierApps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChantierAppsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChantierApps::class);
    }

    public function save(ChantierApps $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ChantierApps $entity, bool $flush = false): void
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

    /**
     * Cette fonction existe pour faire un query pour prendre un certain nombre de clients. 
     * @param ?int $offset Définit un décalage pour les données prises. C'est 0 par défaut
     * @param int $limit Fixe une certaine limite au nombre de données prises. C'est 10 par défaut
     * @return Collection Renvoie une collection des clients
     */
    public function findByLimit(?int $offset = 0, int $limit = 10)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select()
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * Cette fonction existe pour transformer une collection à un array JSON
     * @param array $clients c'est la variable de la liste des clients
     * @return array Renvoie une liste des clients transformer en array JSON
     */
    public function collectionToArray(array $clients)
    {
        $data = [];

        foreach ($clients as $key => $client) {
            $data[] = [
                'codeChantier'          =>  $client->getId(),
                'libelle'               =>  $client->getLibelle(),
                'codeClient'            =>  $client->getCodeClient()->getId(),
                'nom'                   =>  $client->getCodeClient()->getNom(),
                'adr'                   =>  $client->getAdresse(),
                'cp'                    =>  $client->getCP(),
                'ville'                 =>  $client->getVille(),
                'tel'                   =>  $client->getCodeClient()->getTel(),
                'dateDebut'             =>  $client->getDateDebut(),
                'dateFin'               =>  $client->getDateFin(),
                'statut'                =>  $client->getStatut()->getStatut(),
            ];
        }
        
        return $data;
    }

//    /**
//     * @return ChantierApps[] Returns an array of ChantierApps objects
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

//    public function findOneBySomeField($value): ?ChantierApps
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
