<?php

namespace App\Controller;

use App\Entity\SAVSearch;
use App\Form\SAVSearchType;
use App\Repository\ContratRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_GESTION')]
class SAVSearchController extends AbstractController
{
    #[Route('/search/sav', name: 'app_search_sav', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $savSearch = new SAVSearch;
        $form = $this->createForm(SAVSearchType::class, $savSearch, 
        ['action' => $this->generateUrl('app_search_sav')]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            dd($savSearch);
        }

        return $this->render('sav_search/_search-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
