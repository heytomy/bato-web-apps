<?php

namespace App\Controller\Ajax;

use DateTime;
use App\Entity\Calendrier;
use App\Repository\AppelsRepository;
use App\Repository\ContratRepository;
use App\Repository\CalendrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChantierAppsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxController extends AbstractController
{
    /** 
     * Cette fonction reçoit une requette AJAX du fichier: assets/infiniteScrollSAV.js
     * Elle cherche un certain nombre des clients selon les 2 variable $limit et $offset
     * Enfin, elle envoit ces clients comme une réponse JSON
     */
    #[Route('/ajax/sav', name: 'app_ajax_sav' , methods:['POST'])]
    public function getClientsSAV(Request $request, ContratRepository $contratRepository)
    {
        $data = json_decode($request->getContent(), true);
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 10;

        $clients = $contratRepository->findByLimit($offset, $limit);
        $clients = $contratRepository->collectionToArray($clients);
        $total = $contratRepository->getCountClients();

        return $this->json([
            'clients' => $clients,
            'total' => $total,
        ]);
    }

    #[Route('/ajax/appels', name: 'app_appels_sav' , methods:['POST'])]
    public function getClientsAppels(Request $request, AppelsRepository $appelsRepository)
    {
        $data = json_decode($request->getContent(), true);
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 10;

        $appels = $appelsRepository->findByLimit($offset, $limit);
        $appels = $appelsRepository->collectionToArray($appels);
        $total = $appelsRepository->getCountAppels();

        return $this->json([
            'clients' => $appels,
            'total' => $total,
        ]);
    }

    #[Route('/ajax/appels/termine', name: 'app_ajax_appels_termine' , methods:['POST'])]
    public function getClientsAppelsTermine(Request $request, AppelsRepository $appelsRepository)
    {
        $data = json_decode($request->getContent(), true);
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 10;

        $appels = $appelsRepository->findByLimit($offset, $limit, 'TERMINE');
        $appels = $appelsRepository->collectionToArray($appels);
        $total = $appelsRepository->getCountAppels('TERMINE');

        return $this->json([
            'clients' => $appels,
            'total' => $total,
        ]);
    }
    

    #[Route('/ajax/chantier', name: 'app_ajax_chantier' , methods:['POST'])]
    public function getClientsChantier(Request $request, ChantierAppsRepository $chantierAppsRepository)
    {
        $data = json_decode($request->getContent(), true);
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 10;

        $clients = $chantierAppsRepository->findByLimit($offset, $limit);
        $clients = $chantierAppsRepository->collectionToArray($clients);
        $total = $chantierAppsRepository->getCountClients();

        return $this->json([
            'clients' => $clients,
            'total' => $total,
        ]);
    }

    #[Route('/ajax/chantier/termine', name: 'app_ajax_chantier_termine' , methods:['POST'])]
    public function getClientsChantierTermine(Request $request, ChantierAppsRepository $chantierAppsRepository)
    {
        $data = json_decode($request->getContent(), true);
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 10;

        $clients = $chantierAppsRepository->findByLimit($offset, $limit, 'TERMINE');
        $clients = $chantierAppsRepository->collectionToArray($clients);
        $total = $chantierAppsRepository->getCountClients('TERMINE');

        return $this->json([
            'clients' => $clients,
            'total' => $total,
        ]);
    }

    /** 
     * Cette fonction reçoit une requette AJAX du fichier: assets/infiniteScrollSAV.js
     * Elle cherche les clients par le nom envoyée par l'ajax $nom
     * Enfin, elle envoit ces clients comme une réponse JSON
     */
    #[Route('/ajax/sav/search', name: 'app_ajax_sav_search', methods: ['POST'])]
    public function search(Request $request, ContratRepository $contratRepository): JsonResponse
    {
        $search = $data = json_decode($request->getContent(), true);
        $nom = $search['search'] ?? '';

        $data = $contratRepository->findBySAVSearchQuery($nom);
        $clients = $contratRepository->collectionToArray($data);

        return $this->json([
            'clients' => $clients,
            'total' => null,
        ]);
    }

    #[Route('/ajax/calendrier/{id}/edit', name: 'app_ajax_calendrier_edit', methods: ['PUT'])]
    public function majEvent(?Calendrier $booking, Request $request, CalendrierRepository $calendrierRepository, EntityManagerInterface $em)
    {  
        // On récupère les données
        $data = json_decode($request->getContent());

        if(
            isset($data->titre) && !empty($data->titre) &&
            isset($data->dateDebut) && !empty($data->dateDebut)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;
            
            // On hydrate l'objet avec les données
            $booking
                ->setTitre($data->titre)
                ->setDateDebut(new DateTime($data->dateDebut))
                ;
            
            if (null !== $booking->getChantier()) {
                
                $dateDebut = DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $data->dateDebut);
                $dateDebut->setTime(0, 0, 0);

                $booking->setDateDebut($dateDebut);
                $booking->getChantier()->setDateDebut($dateDebut);

                if(isset($data->dateFin) && !empty($data->dateFin)){

                    $dateFin = DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $data->dateFin);
                    $dateFin->setTime(0, 0, 0);

                    $booking->setDateFin($dateFin);
                    $booking->getChantier()->setDateFin($dateFin);
                }
            } else {
                // On met la dateFin à null s'il n'y a pas de donnée, sinon on insert la donnée dateFin
                if (isset($data->allDay)) {
                    if($data->allDay) {
                        $booking->setAllDay($data->allDay);
                        $booking->setDateFin(null);
                    } else {
                        $booking->setAllDay($data->allDay);
                        $dateFin = new DateTime($data->dateDebut);
                        $dateFin->modify('+1 hour');
                        if (null === $booking->getChantier()) {
                            $booking->setDateFin($dateFin);
                        }
                    }
                }
                if(isset($data->dateFin) && !empty($data->dateFin)){
                    $booking->setDateFin(new DateTime($data->dateFin));
    
                    if (null !== $booking->getChantier()) {
                        $dateFin = DateTime::createFromFormat('Y-m-d', $data->dateFin);
        
                        $booking->setDateFin($dateFin);
                        $booking->getChantier()->setDateFin($dateFin);
                    }
                }
            }
            $em->persist($booking);
            $em->flush($booking);

            $em->persist($booking->getChantier());
            $em->flush($booking->getChantier());


            // On rerourne le code
            return new Response('Ok', $code);
        } else {
            // Les données sont incomplètes
            return new Response('Données incomplète', 404);
        }
    }
}


