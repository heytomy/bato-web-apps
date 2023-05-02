<?php

namespace App\Controller\Ajax;

use App\Repository\ChantierAppsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxChantierController extends AbstractController
{
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
}