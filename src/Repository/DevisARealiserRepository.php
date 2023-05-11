<?php

namespace App\Repository;

use App\Entity\DevisARealiser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DevisARealiser>
 *
 * @method DevisARealiser|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevisARealiser|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevisARealiser[]    findAll()
 * @method DevisARealiser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisARealiserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevisARealiser::class);
    }

    public function save(DevisARealiser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DevisARealiser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCountClients(?string $statut = 'EN_COURS')
    {
        $qb = $this->createQueryBuilder('devis')
                ->innerJoin('devis.statut', 's')
                ->andWhere('s.statut = :statut')
                ->setParameter('statut', $statut)
                ;

        $qb->select(
            $qb->expr()->count(x: 'devis.id')
        )
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }
    /**
     * Cette fonction existe pour faire un query pour prendre un certain nombre de clients. 
     * @param ?int $offset Définit un décalage pour les données prises. C'est 0 par défaut
     * @param int $limit Fixe une certaine limite au nombre de données prises. C'est 10 par défaut
     * @param ?string $stat chercher les devis qui sont en cours par defaut
     * @return Collection Renvoie une collection des clients
     */
    public function findByLimit(?int $offset = 0, int $limit = 10, ?string $stat = 'EN_COURS')
    {
        $qb = $this->createQueryBuilder('devis');

        $qb->select()
            ->innerJoin('devis.statut', 's')
            ->andWhere('s.statut = :stat')
            ->setParameter('stat', $stat)
            ->orderBy('devis.nom', 'ASC')
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
                'codeDevis'             =>  $client->getId(),
                'nom'                   =>  $client->getNom(),
                'adr'                   =>  $client->getAdr(),
                'tel'                   =>  $client->getTel(),
                'mail'                  =>  $client->getMail(),
                'date'                  =>  $client->getDate(),
                'description'           =>  $client->getDescription(),
                'statut'                =>  $client->getStatut()->getStatut(),
            ];
        }
        
        return $data;
    }

//    /**
//     * @return DevisARealiser[] Returns an array of DevisARealiser objects
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

//    public function findOneBySomeField($value): ?DevisARealiser
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
