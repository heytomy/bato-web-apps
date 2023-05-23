<?php

namespace App\Controller;

use DateTime;
use App\Entity\Appels;
use App\Entity\ChantierApps;
use App\Entity\DevisARealiser;
use App\Repository\DevisARealiserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StatutChantierRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        EntityManagerInterface $em,
        StatutChantierRepository $statutChantierRepository
        ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $now = new DateTime();

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

        $devisARealiser = $em->getRepository(DevisARealiser::class)->findByStatut('EN_COURS');
        $chantierEnCours = $em->getRepository(ChantierApps::class)->findByStatut('EN_COURS');
        $appelsLast = $em->getRepository(Appels::class)->findByStatut('EN_COURS');


        $em->clear();
        
        return $this->render('dashboard/index.html.twig', [
            'current_page' => 'app_dashboard',
            'appelsCurrentUser' => $appelsCurrentUser,
            'appelsLast' => $appelsLast,
            'devisARealiser' => $devisARealiser,
            'chantierEnCours' => $chantierEnCours,
            'now' => $now
        ]);
    }
}
