<?php

namespace App\Controller\Ajax;

use App\Repository\AppelsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxAppelsController extends AbstractController
{
    #[Route('/ajax/appels', name: 'app_ajax_appels' , methods:['POST'])]
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

    #[Route('/ajax/appels/search', name: 'app_ajax_appels_search', methods: ['POST'])]
    public function search(Request $request, AppelsRepository $appelsRepository): JsonResponse
    {
        $search = $data = json_decode($request->getContent(), true);
        $nom = $search['search'] ?? '';
        $isTermine = $search['isTermine'];

        if ($isTermine) {
            $data = $appelsRepository->findBySearchQuery($nom, 'TERMINE');
        } else {
            $data = $appelsRepository->findBySearchQuery($nom, 'EN_COURS');
        }

        
        $clients = $appelsRepository->collectionToArray($data);

        return $this->json([
            'clients' => $clients,
            'total' => null,
        ]);
    }
}