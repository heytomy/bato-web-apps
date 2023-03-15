<?php

namespace App\Controller;

use App\Repository\ContratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SAVController extends AbstractController
{
    #[Route('/sav', name: 'app_sav')]
    public function index(ContratRepository $contratRepository): Response
    {
        $clients = $contratRepository->findByLimit();

        $newClients = $contratRepository->findByLimitArray();
        
        return $this->render('sav/index.html.twig', [
            'current_page' => 'app_sav',
            'clients' => $clients,
        ]);
    }

    #[Route('/sav/ajax', name: 'app_sav_ajax' , methods:['POST'])]
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
