<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Repository\ContratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/sav')]
#[IsGranted('ROLE_GESTION')]
class SAVController extends AbstractController
{
    #[Route('/', name: 'app_sav')]
    public function index(ContratRepository $contratRepository): Response
    {
        $clients = $contratRepository->findByLimit();

        $newClients = $contratRepository->findByLimitArray();
        
        return $this->render('sav/index.html.twig', [
            'current_page' => 'app_sav',
            'clients' => $clients,
        ]);
    }

    #[Route('/{id}', name: 'app_sav_contrat', methods: ['GET'])]
    public function show(Contrat $contrat, ContratRepository $contratRepository): Response
    {
        return $this->render('sav/show.html.twig', [
            'current_page' => 'app_sav_contrat',
            'contrat' => $contrat,
        ]);
    }

    #[Route('/ajax', name: 'app_sav_ajax' , methods:['POST'])]
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
