<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Appels;
use App\Form\AppelsType;
use App\Entity\Calendrier;
use App\Entity\TicketUrgents;
use App\Entity\CommentairesAppels;
use App\Form\CommentairesAppelsType;
use App\Repository\AppelsRepository;
use App\Entity\RepCommentairesAppels;
use App\Form\RepCommentairesAppelsType;
use App\Repository\ClientDefRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TicketUrgentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentairesAppelsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\RepCommentairesAppelsRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_GESTION')]
class AppelsController extends AbstractController
{
    #[Route('/appels', name: 'app_appels')]
    public function index(AppelsRepository $appelsRepository): Response
    {
        $appels = $appelsRepository->createQueryBuilder('a')
            ->orderBy('a.rdv', 'DESC')
            ->getQuery()
            ->getResult();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('appels/index.html.twig', [
            'current_page' => 'app_appels',
            'appels' => $appels,
        ]);
    }

    #[Route('/appels/new', name: 'app_appels_new')]
    public function new(Request $request, EntityManagerInterface $em, TicketUrgentsRepository $ticketUrgent): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        $appel = new Appels();
        $rdv = new Calendrier();
        $form = $this->createForm(AppelsType::class, $appel);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $rdvDateTime = $form->get('rdvDateTime')->getData()->format('Y-m-d H:i:s');
            
            $allDay = $form->get('allDay')->getData();
            
            $rdvDateTime = new DateTime($rdvDateTime);
            $HoursInterval = new DateInterval('PT2H');
            $rdvHeureFin = clone $rdvDateTime;

            if ($allDay) {
                $rdvHeureFin->setTime(20, 0);
            } else {
                $rdvHeureFin->add($HoursInterval);
            }
            
            $rdv
                ->setDateDebut($rdvDateTime)
                ->setDateFin($rdvHeureFin)
                ->setAllDay($allDay)
                ->setTitre($appel->getNom())
                ;
            
            $appel->setRdv($rdv);

            dd($appel);

            $em->persist($appel);
            $em->flush($appel);

            $em->persist($rdv);
            $em->flush($rdv);
    
            if ($form->get('isUrgent')->getData()) {

                $ticketUrgent = new TicketUrgents();
                $ticketUrgent
                    ->setAppelsUrgents($appel)
                    ->setStatus(0)
                ;
    
                $em->persist($ticketUrgent);
                $em->flush($ticketUrgent);
            }
    
            return $this->redirectToRoute('app_appels');
        }
    
        return $this->render('appels/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/get-client-and-contrats-info/{id}', name:'get_client_and_contrats_info', methods:'GET')]
    public function getClientAndContratsInfo(ClientDefRepository $clientDefRepository, int $id): JsonResponse
    {
        $client = $clientDefRepository->find($id);
    
        $contrats = [];
        foreach ($client->getContrats() as $contrat) {
            $contrats[] = [
                'codecontrat' => $contrat->getId(),
            ];
        }
    
        $data = [
            'codeclient' => $client->getId(),
            'nom' => $client->getNom(),
            'adr' => $client->getAdr(),
            'cp' => $client->getCp(),
            'ville' => $client->getVille(),
            'tel' => $client->getTel(),
            'email' => $client->getEMail(),
            'contrats' => $contrats,
        ];
    
        return new JsonResponse($data);
    }

    #[Route('/appels/{id}', name: 'app_appels_show')]
    public function show(
        Appels $appel, 
        CommentairesAppelsRepository $commentairesAppelsRepository, 
        Request $request, EntityManagerInterface $em, 
        RepCommentairesAppelsRepository $repCommentairesAppelsRepository,
        ): Response
    {
        $user = $this->getUser() ?? null;
        $comments = $commentairesAppelsRepository->findBy(['codeAppels' => $appel->getId()]);
        $nom = $user->getIdUtilisateur()->getNom() ." ". $user->getIdUtilisateur()->getPrenom();

        /**
         * Partie commentaires
         */
        // On crée le formulaires pour les commentaires
        $comment = new CommentairesAppels();
        $commentForm = $this->createForm(CommentairesAppelsType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment
                ->setDateCom(new DateTime())
                ->setNom($nom)
                ->setCodeAppels($appel->getId())
                ->setOwner($user->getIDUtilisateur())
                ;
            if ($appel->getCodeClient()) {
                $comment->setCodeClient($appel->getCodeClient()->getId());
            }
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_appels_show', ['id' => $appel->getId()]);
        }

        /**
         * Partie Réponses
         */
        // On récupère toutes les réponses liées à ce contrat
        $replies = $repCommentairesAppelsRepository->findByAppel($appel->getId()) ?? null;

        // On crée le formulaires pour les réponses
        $reply = new RepCommentairesAppels();
        $replyForm = $this->createForm(RepCommentairesAppelsType::class, $reply);
        $replyForm->handleRequest($request);
 
        if ($replyForm->isSubmitted() && $replyForm->isValid()) {
            $reply
                ->setDateCom(new DateTime())
                ->setNom($nom)
                ->setOwner($user->getIDUtilisateur())
                ;

            // On récupère le contenu du champ parentid
            $parentid = $replyForm->get('parentid')->getData();

            // On va chercher le commentaire correspondant
            $parent = $commentairesAppelsRepository->find($parentid);

            // On définit le parent
            $reply->setParent($parent);

            if ($appel->getCodeClient()) {
                $reply->setCodeClient($appel->getCodeClient()->getId());
            }
            
            $em->persist($reply);
            $em->flush();
            return $this->redirectToRoute('app_appels_show', ['id' => $appel->getId()]);
        }

        return $this->render('appels/show.html.twig',[
            'appel'             =>  $appel,
            'comments'          =>  $comments,
            'restCommentaires'  =>  null,
            'commentForm'       =>  $commentForm,
            'replyForm'         =>  $replyForm,
            'replies'           =>  $replies,
        ]);
    }
}
