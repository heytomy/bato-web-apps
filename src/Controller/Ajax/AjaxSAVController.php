<?php

namespace App\Controller\Ajax;

use App\Repository\ContratRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxSAVController extends AbstractController
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
}