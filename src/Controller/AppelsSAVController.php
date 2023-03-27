<?php

namespace App\Controller;

use App\Entity\AppelsSAV;
use App\Form\AppelsSAVType;
use App\Repository\AppelsSAVRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
class AppelsSAVController extends AbstractController
{
    #[Route('/appels_sav', name: 'app_appels_sav')]
    public function index(AppelsSAVRepository $appelsSAVRepository): Response
    {
        $appelsSAV = $appelsSAVRepository->findAll();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('appels_sav/index.html.twig', [
            'appelsSAV' => $appelsSAV,
        ]);
    }

    #[Route('/appels_sav/new', name: 'app_appels_sav_new')]
    public function new(Request $request, AppelsSAVRepository $appelsSAVRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $appelSAV = new AppelsSAV();
        $form = $this->createForm(AppelsSAVType::class, $appelSAV);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($appelSAV);
            $em->flush();

            return $this->redirectToRoute('app_appels');
        }

        return $this->render('appels_sav/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}