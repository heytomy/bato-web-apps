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

    /**
     * Cette fonction existe pour faire un query pour prendre un certain nombre d'appels. 
     * @param ?int $offset Définit un décalage pour les données prises. C'est 0 par défaut
     * @param int $limit Fixe une certaine limite au nombre de données prises. C'est 10 par défaut
     * @param ?string $stat chercher les appels qui sont en cours par defaut
     * @return Collection Renvoie une collection des appels
     */
    public function findByLimit(?int $offset = 0, int $limit = 10, ?string $stat = 'EN_COURS')
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select()
            ->innerJoin('a.statut', 's')
            ->andWhere('s.statut = :stat')
            ->setParameter('stat', $stat)
            ->orderBy('a.id', 'DESC')
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
    public function collectionToArray(array $appels)
    {
        $data = [];

        foreach ($appels as $key => $appel) {
            $data[] = [
                'codeAppel'             =>  $appel->getId(),
                'description'           =>  $appel->getDescription(),
                // 'codeClient'            =>  $appel->getCodeClient()->getId(),
                // 'codeContrat'           =>  $appel->getCodeContrat()->getId(),
                'nom'                   =>  $appel->getNom(),
                'adr'                   =>  $appel->getAdr(),
                'cp'                    =>  $appel->getCP(),
                'ville'                 =>  $appel->getVille(),
                'tel'                   =>  $appel->getTel(),
                'email'                 =>  $appel->getEmail(),
                'dateDebut'             =>  $appel->getRdv()->getDateDebut(),
                'dateFin'               =>  $appel->getRdv()->getDateFin(),
                'technicien'            =>  $appel->getIDUtilisateur()->getIdUtilisateur()->getNom(),
                'statut'                =>  $appel->getStatut()->getStatut(),
                'isUrgent'              =>  $appel->isUrgent(),
            ];

            if ($appel->getCodeClient()) {
                $data[count($data)-1]['codeClient'] = $appel->getCodeClient()->getId();
            }
            if ($appel->getCodeContrat()) {
                $data[count($data)-1]['codeContrat'] = $appel->getCodeContrat()->getId();
            }
        }
        
        return $data;
    }

    public function getCountAppels(?string $statut = 'EN_COURS')
    {
        $qb = $this->createQueryBuilder('a')
                ->innerJoin('a.statut', 's')
                ->andWhere('s.statut = :statut')
                ->setParameter('statut', $statut);

        $qb->select(
            $qb->expr()->count(x: 'a.id')
        )
        ;
            

        return $qb->getQuery()->getSingleScalarResult();
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
