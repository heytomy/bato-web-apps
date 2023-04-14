<?php

namespace App\Controller;

use App\Entity\Appels;
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
    
        // Get all new appointments from the last day
        $now = new \DateTime();
        $oneDayAgo = (new \DateTime())->sub(new \DateInterval('P1D'));
    
        $newAppels = $em->getRepository(Appels::class)->createQueryBuilder('a')
            ->where('a.createdAt BETWEEN :oneDayAgo AND :now')
            ->setParameter('oneDayAgo', $oneDayAgo)
            ->setParameter('now', $now)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    
        // Get the count of new appointments from the last day
        $countNewAppels = $em->getRepository(Appels::class)->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.createdAt BETWEEN :oneDayAgo AND :now')
            ->setParameter('oneDayAgo', $oneDayAgo)
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();
    
        $em->clear();
    
        return $this->render('dashboard/index.html.twig', [
            'current_page' => 'app_dashboard',
            'newAppels' => $newAppels,
            'countNewAppels' => $countNewAppels,
        ]);
    }
    
}
