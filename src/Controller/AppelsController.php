<?php

namespace App\Controller;

use DateTime;
use App\Entity\Appels;
use App\Form\AppelsType;
use App\Entity\TicketUrgents;
use App\Repository\AppelsRepository;
use App\Repository\ClientDefRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TicketUrgentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_GESTION')]
class AppelsController extends AbstractController
{
    #[Route('/appels', name: 'app_appels')]
    public function index(AppelsRepository $appelsRepository): Response
    {
        $appels = $appelsRepository->findAll();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('appels/index.html.twig', [
            'current_page' => 'app_appels',
            'appels' => $appels,
        ]);
    }

    #[Route('/appels/new', name: 'app_appels_new')]
    public function new(Request $request, AppelsRepository $appelsRepository, EntityManagerInterface $em, TicketUrgentsRepository $ticketUrgent): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        $appel = new Appels();
        $form = $this->createForm(AppelsType::class, $appel);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // $rdvDate = $form->get('rdvDate')->getData()->format('Y-m-d');
            // $rdvTime = $form->get('rdvTime')->getData()->format('H:i:s');
            // $rdvDateHour = $rdvDate . ' ' . $rdvTime;

            // $dateTime = new DateTime($rdvDateHour);

            // $appelSAV->setRdvDateTime($dateTime);

            // dd($dateTime);

            $em->persist($appel);
            $em->flush($appel);
    
            if ($form->get('isUrgent')->getData()) {

                $ticketUrgent = new TicketUrgents();
                $ticketUrgent
                    ->setAppelsUrgents($appel)
                    ->setStatus(0)
                ;
    
                $em->persist($ticketUrgent);
                $em->flush($ticketUrgent);
            }
    
            return $this->redirectToRoute('app_appels_new');
        }
    
        return $this->render('appels/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/get-client-and-contrats-info/{id}', name:'get_client_and_contrats_info', methods:'GET')]
    public function getClientAndContratsInfo(ClientDefRepository $clientDefRepository, int $id): JsonResponse
    {
        $client = $clientDefRepository->find($id);
    
        $contrats = [];
        foreach ($client->getContrats() as $contrat) {
            $contrats[] = [
                'codecontrat' => $contrat->getId(),
            ];
        }
    
        $data = [
            'codeclient' => $client->getId(),
            'nom' => $client->getNom(),
            'adr' => $client->getAdr(),
            'cp' => $client->getCp(),
            'ville' => $client->getVille(),
            'tel' => $client->getTel(),
            'email' => $client->getEMail(),
            'contrats' => $contrats,
        ];
    
        return new JsonResponse($data);
    }
}
