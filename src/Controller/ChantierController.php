<?php

namespace App\Controller;

use App\Entity\ChantierApps;
use App\Form\ChantierAppsType;
use App\Repository\ChantierAppsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chantier')]
#[IsGranted('ROLE_GESTION')]
class ChantierController extends AbstractController
{
    #[Route('/', name: 'app_chantier')]
    public function index(): Response
    {
        return $this->render('chantier/index.html.twig', [
            'current_page' => 'app_chantier',
        ]);
    }

    #[Route('/new', name: 'app_chantier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChantierAppsRepository $chantierAppsRepository): Response
    {
        $chantier = new ChantierApps();
        $form = $this->createForm(ChantierAppsType::class, $chantier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chantierAppsRepository->save($chantier, true);

            return $this->redirectToRoute('app_chantier', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chantier/new.html.twig', [
            'chantier'      =>  $chantier,
            'form'          =>  $form,
            'current_page'  =>  'app_chantier',
        ]);
    }
}
