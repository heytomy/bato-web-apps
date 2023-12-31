<?php

namespace App\Controller;

use App\Form\MyFormData;
use App\Form\MyFormType;
use App\Entity\ClientDef;
use App\Entity\AppsUtilisateur;
use App\Repository\RolesRepository;
use App\Repository\AppelsRepository;
use App\Repository\ContratRepository;
use App\Repository\ClientDefRepository;
use App\Repository\CalendrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppsUtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DefAppsUtilisateurRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Ce controlleur existe pour faire les testes pendant le développement
 * Require ROLE_ADMIN for only this controller method.
 */
#[IsGranted('ROLE_ADMIN')]
class TestController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/test/{id}', name: 'app_test_1', methods: ['POST', 'GET'])]
    public function user(
        AppsUtilisateur $user, 
        RolesRepository $rolesRepository,
        ContratRepository $contratRepository, 
        AppelsRepository $appelsRepository,
        CalendrierRepository $calendrierRepository,
        ClientDefRepository $clientDefRepository,
        Request $request,
        ): Response
    {
        $contrat = $contratRepository->findOneBy(['libelle' => 'Gaz condensation']);
        $calendriers = $calendrierRepository ->findAll();

        
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('test/test.html.twig', [
            'controller_name' => 'TestController',
            'user' => $user,
            'clients' => $contratRepository->findByLimit(0,1000),
            'total' => $contratRepository->getCountClients(),
        ]);
    }

    #[Route('/test', name: 'app_test')]
    public function index(AppsUtilisateurRepository $appsUtilisateurRepository, DefAppsUtilisateurRepository $defAppsUtilisateurRepository): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'users' => $appsUtilisateurRepository->findAll(),
        ]);

    }

    // #[Route('/comment/{parentId}/reply', name: 'app_sav_reply', methods:['GET' ,'POST'])]
    // public function reply(
    //     int $parentId, 
    //     Request $request, 
    //     EntityManagerInterface $em, 
    //     CommentairesSAVRepository $commentairesSAVRepository
    //     ): Response
    // {
    //     $user = $this->getUser() ?? null;
    //     $nom = $user->getIdUtilisateur()->getNom() ." ". $user->getIdUtilisateur()->getPrenom();
    //     $parent = $commentairesSAVRepository->findOneBy(['id' => $parentId]);

    //     $reply = new RepCommentairesSAV();
    //     $replyForm = $this->createForm(RepCommentairesSAVType::class, $reply);
    //     $replyForm->handleRequest($request);

    //     if ($replyForm->isSubmitted() && $replyForm->isValid()) {
    //         dump('we reached form submit');
    //         $reply
    //             ->setDateCom(new DateTime())
    //             ->setParent($parent[0])
    //             ->setCodeClient($parent->getCodeClient())
    //             ->setNom($nom)
    //             ->setOwner($user->getIDUtilisateur())
    //             ;
            
    //         $em->persist($reply);
    //         $em->flush();
    //         return $this->redirectToRoute('app_sav_contrat', ['id' => $parent->getCodeContrat()->getId()]);
    //     }

    //     return $this->render('_inc/_reply-form.html.twig', [
    //         'form' => $replyForm->createView(),
    //     ]);
    // }
}
