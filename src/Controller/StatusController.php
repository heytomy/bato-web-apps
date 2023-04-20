<?php

namespace App\Controller;

use App\Entity\ChantierApps;
use App\Entity\StatutChantier;
use App\Repository\StatutChantierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_GESTION')]
class StatusController extends AbstractController
{
    #[Route('/status/change/chantier/{id}', name: 'app_status_change_chantier', methods:['POST'])]
    public function changeStatusChantier(
        Request $request, 
        ChantierApps $chantier, 
        EntityManagerInterface $em,
        StatutChantierRepository $statutChantierRepository,
        ): Response
    {
        if ($this->isCsrfTokenValid('change_status_'.$chantier->getId(), $request->request->get('_token'))) {
            $statutTermine = $statutChantierRepository->findOneBy(['statut' => 'TERMINE']);
            $chantier->setStatut($statutTermine);
            $em->persist($chantier);
            $em->flush();

            $this->addFlash(
                'success',
                'Le statut du chantier a été modifié'
            );
            // Redirect to the page that displays your entity
            return $this->redirectToRoute('app_chantier', []);
        }
        $this->addFlash(
           'error',
           'Le statut ne peut pas être changé'
        );
        return $this->redirectToRoute('app_chantier_show', ['id' => $chantier->getId()]);
    }
}
