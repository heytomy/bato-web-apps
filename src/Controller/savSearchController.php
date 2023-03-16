<?php

namespace App\Controller;

use App\Entity\SAVSearch;
use App\Form\SAVSearchType;
use App\Repository\ContratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_GESTION')]
class SAVSearchController extends AbstractController
{
    #[Route('/sav/search', name: 'app_sav_search')]
    public function index(Request $request, ContratRepository $contratRepository): Response
    {
        $savSearch = new SAVSearch;
        $form = $this->createForm(SAVSearchType::class, $savSearch);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $clients = $contratRepository->findBySAVSearchQuery($savSearch);
        }

        return $this->render('sav_search/_search-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
