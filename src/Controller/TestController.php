<?php

namespace App\Controller;

use App\Entity\AppsUtilisateur;
use App\Repository\AppsUtilisateurRepository;
use App\Repository\ContratRepository;
use App\Repository\DefAppsUtilisateurRepository;
use App\Repository\RolesRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function user(AppsUtilisateur $user, RolesRepository $rolesRepository, ContratRepository $contratRepository, EntityManagerInterface $em): Response
    {
        $connection = $em->getConnection();

        $sql = "SELECT * FROM Apps_Utilisateur WHERE id = :id";
        $stmt = $connection->executeQuery($sql, ['id' => $user->getId()]);

        $test2 = $stmt->fetchAssociative();
        dd($contratRepository->findAll());

        // $test = $contratRepository->find("00001");
        // dd($test);
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('test/test.html.twig', [
            'controller_name' => 'TestController',
            'user' => $user,
            'clients' => $contratRepository->findByLimitArray(0,1000),
            'total' => $contratRepository->getCountClients(),
        ]);
    }

    #[Route('/test', name: 'app_test')]
    public function index(AppsUtilisateurRepository $appsUtilisateurRepository, DefAppsUtilisateurRepository $defAppsUtilisateurRepository): Response
    {

        $clients = $defAppsUtilisateurRepository->findAll();
        $users = $appsUtilisateurRepository->findBy(['roles' => 3 ]);
        dd($users);



        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'users' => $appsUtilisateurRepository->findAll(),
        ]);

    }
}
