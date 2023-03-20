<?php

namespace App\Controller;

use App\Entity\CommentairesSAV;
use App\Entity\Contrat;
use App\Repository\CommentairesSAVRepository;
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
        return $this->render('sav/index.html.twig', [
            'current_page' => 'app_sav',
        ]);
    }

    #[Route('/{id}', name: 'app_sav_contrat', methods: ['GET'])]
    public function show(Contrat $contrat, CommentairesSAVRepository $commentairesSAVRepository): Response
    {
        $clientId = $contrat->getCodeClient()->getId();
        $comments = $commentairesSAVRepository->findBy(['codeClient' => $clientId]);

        return $this->render('sav/show.html.twig', [
            'current_page' => 'app_sav',
            'contrat' => $contrat,
            'comments' => $comments,
            'restCommentaires' => null,
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
