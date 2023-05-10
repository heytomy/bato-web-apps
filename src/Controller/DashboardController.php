<?php

namespace App\Controller;

use App\Entity\Appels;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUserId = $this->getUser()->getId();

        $appelsCurrentUser = $em->getRepository(Appels::class)->createQueryBuilder('a')
            ->innerJoin('a.rdv', 'rdv')
            ->where('a.ID_Utilisateur = :userId')
            ->setParameter('userId', $currentUserId)
            ->andWhere('rdv.dateDebut >= :today')
            ->andWhere('rdv.dateDebut < :tomorrow')
            ->setParameter('today', new \DateTime('today'))
            ->setParameter('tomorrow', new \DateTime('tomorrow'))
            ->getQuery()
            ->getResult(); 

        $em->clear();
        
        return $this->render('dashboard/index.html.twig', [
            'current_page' => 'app_dashboard',
            'appelsCurrentUser' => $appelsCurrentUser,
        ]);
    }
}
