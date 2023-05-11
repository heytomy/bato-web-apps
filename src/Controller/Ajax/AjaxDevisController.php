<?php

namespace App\Controller\Ajax;

use App\Repository\DevisARealiserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxDevisController extends AbstractController
{
    #[Route('/ajax/devis', name: 'app_ajax_devis' , methods:['POST'])]
    public function getClientsDevis(Request $request, DevisARealiserRepository $devisARealiserRepository)
    {
        $data = json_decode($request->getContent(), true);
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 10;

        $clients = $devisARealiserRepository->findByLimit($offset, $limit);
        $clients = $devisARealiserRepository->collectionToArray($clients);
        $total = $devisARealiserRepository->getCountClients();

        return $this->json([
            'clients' => $clients,
            'total' => $total,
        ]);
    }

    #[Route('/ajax/devis/termine', name: 'app_ajax_devis_termine' , methods:['POST'])]
    public function getClientsDevisTermine(Request $request, DevisARealiserRepository $devisARealiserRepository)
    {
        $data = json_decode($request->getContent(), true);
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 10;

        $clients = $devisARealiserRepository->findByLimit($offset, $limit, 'TERMINE');
        $clients = $devisARealiserRepository->collectionToArray($clients);
        $total = $devisARealiserRepository->getCountClients('TERMINE');

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
    // #[Route('/ajax/chantier/search', name: 'app_ajax_chantier_search', methods: ['POST'])]
    // public function search(Request $request, ChantierAppsRepository $chantierAppsRepository): JsonResponse
    // {
    //     $search = $data = json_decode($request->getContent(), true);
    //     $nom = $search['search'] ?? '';
    //     $isTermine = $search['isTermine'];

    //     if ($isTermine) {
    //         $data = $chantierAppsRepository->findBySearchQuery($nom, 'TERMINE');
    //     } else {
    //         $data = $chantierAppsRepository->findBySearchQuery($nom, 'EN_COURS');
    //     }

        
    //     $clients = $chantierAppsRepository->collectionToArray($data);

    //     return $this->json([
    //         'clients' => $clients,
    //         'total' => null,
    //     ]);
    // }
}