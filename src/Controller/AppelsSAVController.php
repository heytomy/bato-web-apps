<?php

namespace App\Controller;

use App\Entity\AppelsSAV;
use App\Entity\Contrat;
use App\Form\AppelsSAVType;
use App\Entity\TicketUrgents;
use App\Repository\AppelsSAVRepository;
use App\Repository\ClientDefRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TicketUrgentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;

#[IsGranted('ROLE_ADMIN')]
class AppelsSAVController extends AbstractController
{
    #[Route('/appels_sav', name: 'app_appels_sav')]
    public function index(AppelsSAVRepository $appelsSAVRepository): Response
    {
        $appelsSAV = $appelsSAVRepository->findAll();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('appels_sav/index.html.twig', [
            'appelsSAV' => $appelsSAV,
        ]);
    }

    #[Route('/appels_sav/new', name: 'app_appels_sav_new')]
    public function new(Request $request, AppelsSAVRepository $appelsSAVRepository, EntityManagerInterface $em, TicketUrgentsRepository $ticketUrgent): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        $appelSAV = new AppelsSAV();
        $form = $this->createForm(AppelsSAVType::class, $appelSAV);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $rdvDate = $form->get('rdvDate')->getData()->format('Y-m-d');
            $rdvTime = $form->get('rdvTime')->getData()->format('H:i:s');
            $rdvDateHour = $rdvDate . ' ' . $rdvTime;

            $dateTime = new DateTime($rdvDateHour);

            $appelSAV->setRdvDateTime($dateTime);

            // dd($dateTime);

            $em->persist($appelSAV);
            $em->flush($appelSAV);
    
            if ($form->get('isUrgent')->getData()) {

                $ticketUrgent = new TicketUrgents();
                $ticketUrgent->setAppelsSAV($appelSAV);
    
                $em->persist($ticketUrgent);
                $em->flush($ticketUrgent);
            }
    
            return $this->redirectToRoute('app_appels_sav_new');
        }
    
        return $this->render('appels_sav/new.html.twig', [
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
