<?php

namespace App\Controller;

use App\Entity\Appels;
use App\Entity\ClientDef;
use App\Form\AppelsType;
use App\Repository\AppelsRepository;
use App\Repository\ClientDefRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
class AppelsController extends AbstractController
{
    #[Route('/appels', name: 'app_appels')]
    public function index(AppelsRepository $appelsRepository): Response
    {
        $appels = $appelsRepository->findAll();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('appels/index.html.twig', [
            'current_page' => 'app_appels',
            'appels' => $appels,
        ]);
    }

    #[Route('/appels/new', name: 'app_appels_new')]
    public function new(Request $request, AppelsRepository $appelsRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $appel = new Appels();
        $form = $this->createForm(AppelsType::class, $appel);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($appel);
            $em->flush();
    
            return $this->redirectToRoute('app_appels');
        }
    
        return $this->render('appels/new.html.twig', [
            'current_page' => 'app_appels',
            'form' => $form->createView(),
        ]);
    }  
}
