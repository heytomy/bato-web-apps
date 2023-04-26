<?php

namespace App\Controller\Admin;

use App\Entity\ClientDef;
use App\Entity\AppsUtilisateur;
use App\Entity\DefAppsUtilisateur;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Controller\Admin\ClientDefCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use App\Controller\Admin\AppsUtilisateurCrudController;
use App\Controller\AppelsController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {

    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $url = $this->adminUrlGenerator
        ->setController(AppsUtilisateurCrudController::class)
        ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bato Admin Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Go back', 'fa fa-home', 'app_dashboard');

        yield MenuItem::linkToDashboard('Dashboard', 'fa-solid fa-gear');

        yield MenuItem::linkToCrud('Clients', 'fa fa-plus', ClientDef::class);

        yield menuItem::section('Users');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-plus', AppsUtilisateur::class);
        yield MenuItem::linkToCrud('DefUtilisateurs', 'fa fa-plus', DefAppsUtilisateur::class);


    }
}
