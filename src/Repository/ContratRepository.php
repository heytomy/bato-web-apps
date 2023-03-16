<?php

namespace App\Repository;

use App\Entity\Contrat;
use App\Entity\SAVSearch;
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
     * Cette fonction existe pour faire un query pour prendre un certain nombre de clients. Mais cette fonction renvoie une liste, qu'on va le transmettre en JSON.
     * @param ?int $offset Définit un décalage pour les données prises. C'est 0 par défaut
     * @param int $limit Fixe une certaine limite au nombre de données prises. C'est 10 par défaut
     * @return array Renvoie une liste des clients
     */
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

    public function findBySAVSearchQuery(SAVSearch $savSearch)
    {
        $query = $this->createQueryBuilder('c');
        if ($savSearch->getNom() !== null) {
            $query
                ->andWhere('c.nom > :nom')
                ->setParameter(':nom', $savSearch->getNom())
            ;
        }
        return $query->getQuery();
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
