<?php

namespace App\Controller;

use App\Entity\SAVSearch;
use App\Form\SAVSearchType;
use App\Repository\ContratRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_GESTION')]
class SAVSearchController extends AbstractController
{
    #[Route('/sav/search', name: 'app_sav_search', methods: ['GET', 'POST'])]
    public function index(Request $request, ContratRepository $contratRepository): Response
    {
        $savSearch = new SAVSearch;
        $form = $this->createForm(SAVSearchType::class, $savSearch);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd('Hi');
        }

        return $this->render('sav_search/_search-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
