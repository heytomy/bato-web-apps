<?php

namespace App\Controller;

use App\Entity\AppsUtilisateur;
use App\Repository\AppsUtilisateurRepository;
use App\Repository\RolesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * Ce controlleur existe pour faire les testes pendant le développement
 * Require ROLE_ADMIN for only this controller method.
 */
#[IsGranted('ROLE_ADMIN')]
class TestController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/test/{id}', name: 'app_test_1')]
    public function user(AppsUtilisateur $user, RolesRepository $rolesRepository): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('test/test.html.twig', [
            'controller_name' => 'TestController',
            'user' => $user,
        ]);
    }

    #[Route('/test', name: 'app_test')]
    public function index(AppsUtilisateurRepository $appsUtilisateurRepository): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'users' => $appsUtilisateurRepository->findAll(),
        ]);
    }
}
