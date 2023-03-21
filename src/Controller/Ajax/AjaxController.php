<?php

namespace App\Controller\Ajax;

use App\Repository\ContratRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxController extends AbstractController
{
    
    #[Route('/ajax/sav', name: 'app_ajax_sav' , methods:['POST'])]
    public function getClients(Request $request, ContratRepository $contratRepository)
    {
        $data = json_decode($request->getContent(), true);
        $offset = $data['offset'] ?? 0;
        $limit = $data['limit'] ?? 10;

        $clients = $contratRepository->findByLimitArray($offset, $limit);
        $total = $contratRepository->getCountClients();

        return $this->json([
            'clients' => $clients,
            'total' => $total,
        ]);
    }
}


