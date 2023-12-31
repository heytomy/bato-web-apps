<?php

namespace App\Controller;

use DateTime;
use App\Entity\Appels;
use App\Form\AppelsType;
use App\Entity\Calendrier;
use App\Form\AppelsEditType;
use App\Entity\TicketUrgents;
use App\Entity\CommentairesAppels;
use App\Form\CommentairesAppelsType;
use App\Repository\AppelsRepository;
use App\Entity\RepCommentairesAppels;
use App\Form\RepCommentairesAppelsType;
use App\Repository\ClientDefRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PhotosAppelsRepository;
use App\Repository\TicketUrgentsRepository;
use App\Repository\StatutChantierRepository;
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
        $appels = $appelsRepository->findByStatut('EN_COURS');

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('appels/index.html.twig', [
            'current_page' => 'app_appels',
            'appels' => $appels,
        ]);
    }

    #[Route('/appels/new', name: 'app_appels_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        TicketUrgentsRepository $ticketUrgent,
        StatutChantierRepository $statutChantierRepository
        ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $statutEnCours = $statutChantierRepository->findOneBy(['statut' => 'EN_COURS']);
        $appel = new Appels();
        $rdv = new Calendrier();
        $form = $this->createForm(AppelsType::class, $appel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dateDebut = $form->get('dateDebut')->getData()->format('Y-m-d H:i:s');
            $dateFin = $form->get('dateFin')->getData();

            if (!$form->get('allDay')->getData() && $dateFin === null) {
                $dateFin = (new DateTime($dateDebut))->modify('+1 hour');
            } elseif ($dateFin !== null && $form->get('allDay')->getData()) {
                $dateFin = null;
            }
            
            if ($dateFin !== null) {
                $dateFin = $dateFin->format('Y-m-d H:i:s');
            
                $rdv
                    ->setDateFin(new DateTime($dateFin))
                    ->setAllDay(false);
            } elseif ($form->get('allDay')->getData()) {
                $rdv
                    ->setAllDay(true)
                    ->setDateFin(null);
            } else {
                $rdv
                    ->setAllDay(false)
                    ->setDateFin(null);
            }
            
            if ($dateFin !== null && new DateTime($dateDebut) > new DateTime($dateFin)) {
                $this->addFlash(
                    'error',
                    'La date et heure de fin doit être postérieure à la date et heure de début.'
                );
            } else {
                $rdv
                    ->setDateDebut($appel->getDateDebut())
                    ->setTitre($appel->getNom());
            
                $appel
                    ->setStatut($statutEnCours)
                    ->setRdv($rdv)
                    ->setDescription(strip_tags($form->get('description')->getData()))
                    ->setCreatedAt(new \DateTimeImmutable());
            
                $em->persist($appel);
                $em->flush($appel);
            
                if ($form->get('isUrgent')->getData() && $form->get('status')->getData()) {
                    $status = $form->get('status')->getData();
            
                    $ticketUrgent = new TicketUrgents();
                    $ticketUrgent
                        ->setAppelsUrgents($appel)
                        ->setStatus($status);

                    $rdv
                        ->setAllDay(true)
                        ->setDateFin(null);
            
                    $em->persist($ticketUrgent);
                    $em->flush($ticketUrgent);
                }

                $em->persist($rdv);
                $em->flush($rdv);
            
                $this->addFlash(
                    'success',
                    'Rendez-vous enregistré avec succès!'
                );
            
                return $this->redirectToRoute('app_appels');
            }
                      
        }

        return $this->render('appels/new.html.twig', [
            'form' => $form->createView(),
            'current_page' => 'app_appels',
        ]);
    }

    #[Route('/appels/{id}/edit', name: 'app_appels_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Appels $appel,
        AppelsRepository $appelsRepository,
        EntityManagerInterface $em,
        StatutChantierRepository $statutChantierRepository
    ): Response {

        $statutEnCours = $statutChantierRepository->findOneBy(['statut' => 'EN_COURS']);

        $form = $this->createForm(AppelsEditType::class, $appel);
        $form->handleRequest($request);
        $rdv = $appel->getRdv();

        if ($form->isSubmitted() && $form->isValid()) {

            $dateDebut = $form->get('dateDebut')->getData()->format('Y-m-d H:i:s');
            $dateFin = $form->get('dateFin')->getData();
            
            if (!$form->get('allDay')->getData() && $dateFin === null) {
                $dateFin = (new DateTime($dateDebut))->modify('+1 hour');
            } elseif ($dateFin !== null && $form->get('allDay')->getData()) {
                $dateFin = null;
            }
            
            if ($dateFin !== null) {
                $dateFin = $dateFin->format('Y-m-d H:i:s');
            
                $rdv
                    ->setDateFin(new DateTime($dateFin))
                    ->setAllDay(false);
            } elseif ($form->get('allDay')->getData()) {
                $rdv
                    ->setAllDay(true)
                    ->setDateFin(null);
            } else {
                $rdv
                    ->setAllDay(false)
                    ->setDateFin(null);
            }
            
            if ($dateFin !== null && new DateTime($dateDebut) > new DateTime($dateFin)) {
                $this->addFlash(
                    'error',
                    'La date et heure de fin doit être postérieure à la date et heure de début.'
                );
            } else {
                $rdv
                    ->setDateDebut($appel->getDateDebut())
                    ->setTitre($appel->getNom());
            
                $appel
                    ->setStatut($statutEnCours)
                    ->setRdv($rdv)
                    ->setDescription(strip_tags($form->get('description')->getData()))
                    ->setCreatedAt(new \DateTimeImmutable());
            
                $em->persist($appel);
                $em->flush($appel);
            
                if ($form->get('isUrgent')->getData() && $form->get('status')->getData()) {
                    $status = $form->get('status')->getData();
            
                    $ticketUrgent = new TicketUrgents();
                    $ticketUrgent
                        ->setAppelsUrgents($appel)
                        ->setStatus($status);

                    $rdv
                        ->setAllDay(true)
                        ->setDateFin(null);
            
                    $em->persist($ticketUrgent);
                    $em->flush($ticketUrgent);
                }

                $em->persist($rdv);
                $em->flush($rdv);
            

            $this->addFlash(
                'success',
                'Rendez-vous modifié avec succès !'
            );


            return $this->redirectToRoute('app_appels');
        }
        }

        return $this->render('appels/edit.html.twig', [
            'appel'         =>  $appel,
            'form'          =>  $form,
            'current_page'  =>  'app_appels',
        ]);
    }


    #[Route('/appels/{id}/delete', name: 'app_appels_delete', methods: ['POST'])]
    public function delete(Request $request, Appels $appel, AppelsRepository $appelsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appel->getId(), $request->request->get('_token'))) {
            $appelsRepository->remove($appel, true);
        }

        return $this->redirectToRoute('app_appels', [], Response::HTTP_SEE_OTHER);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/get-client-and-contrats-info/{id}', name: 'get_client_and_contrats_info', methods: 'GET')]
    public function getClientAndContratsInfo(ClientDefRepository $clientDefRepository, int $id): JsonResponse
    {
        $client = $clientDefRepository->find($id);

        $contrats = [];
        foreach ($client->getContrats() as $contrat) {
            $contrats[] = [
                'codecontrat' => $contrat->getId(),
                'libelle' => $contrat->getLibelle(),
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

    #[Route('/appels/{id}', name: 'app_appels_show', methods: ['GET', 'POST'])]
    public function show(
        Appels $appel,
        CommentairesAppelsRepository $commentairesAppelsRepository,
        Request $request,
        EntityManagerInterface $em,
        RepCommentairesAppelsRepository $repCommentairesAppelsRepository,
        PhotosAppelsRepository $photosAppelsRepository,
    ): Response {
        $user = $this->getUser() ?? null;
        $comments = $commentairesAppelsRepository->findBy(['codeAppels' => $appel]);
        $nom = $user->getIdUtilisateur()->getNom() . " " . $user->getIdUtilisateur()->getPrenom();

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
                ->setCodeAppels($appel)
                ->setOwner($user->getIDUtilisateur());
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
                ->setOwner($user->getIDUtilisateur());

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

        /**
         * partie photos
         */
        $photos = $photosAppelsRepository->findBy(['idAppel' => $appel->getId()]) ?? null;

        return $this->render('appels/show.html.twig', [
            'appel'             =>  $appel,
            'comments'          =>  $comments,
            'restCommentaires'  =>  null,
            'commentForm'       =>  $commentForm,
            'replyForm'         =>  $replyForm,
            'replies'           =>  $replies,
            'photos'            =>  $photos,
            'current_page'      =>  'app_appels',
        ]);
    }
}
