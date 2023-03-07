<?php

namespace App\Controller;

use App\Entity\AppsUtilisateur;
use App\Repository\AppsUtilisateurRepository;
use App\Repository\RolesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Ce controlleur existe pour faire les testes pendant le développement
 */
class TestController extends AbstractController
{
    #[Route('/test/{id}', name: 'app_test_1')]
    public function user(AppsUtilisateur $user, RolesRepository $rolesRepository): Response
    {
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
