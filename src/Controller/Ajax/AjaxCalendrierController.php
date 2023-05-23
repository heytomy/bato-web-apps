<?php

namespace App\Controller\Ajax;

use DateTime;
use App\Entity\Calendrier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxCalendrierController extends AbstractController
{
    #[Route('/ajax/calendrier/{id}/edit', name: 'app_ajax_calendrier_edit', methods: ['PUT'])]
    public function majEvent(?Calendrier $booking, Request $request, EntityManagerInterface $em)
    {
        // On récupère les données
        $data = json_decode($request->getContent());

        if (
            isset($data->titre) && !empty($data->titre) &&
            isset($data->dateDebut) && !empty($data->dateDebut)
        ) {
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On hydrate l'objet avec les données
            $booking
                ->setTitre($data->titre)
                ->setDateDebut(new DateTime($data->dateDebut));

            if (null !== $booking->getAppels()) {

                $dateDebut = DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $data->dateDebut);
                $dateDebut->setTime(0, 0, 0);

                $booking->setDateDebut($dateDebut);
                $booking->getAppels()->setDateDebut($dateDebut);

                if (isset($data->dateFin) && !empty($data->dateFin)) {

                    $dateFin = DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $data->dateFin);
                    $dateFin->setTime(0, 0, 0);

                    $booking->setDateFin($dateFin);
                    $booking->getAppels()->setDateFin($dateFin);
                }

                $em->persist($booking->getAppels());
                $em->flush($booking->getAppels());
            } else {
                // On met la dateFin à null s'il n'y a pas de donnée, sinon on insert la donnée dateFin
                if (isset($data->allDay)) {
                    if ($data->allDay) {
                        $booking->setAllDay($data->allDay);
                        $booking->setDateFin(null);
                    } else {
                        $booking->setAllDay($data->allDay);
                        $dateFin = new DateTime($data->dateDebut);
                        $dateFin->modify('+1 hour');
                        if (null === $booking->getAppels()) {
                            $booking->setDateFin($dateFin);
                        }
                    }
                }
                if (isset($data->dateFin) && !empty($data->dateFin)) {
                    $booking->setDateFin(new DateTime($data->dateFin));

                    if (null !== $booking->getAppels()) {
                        $dateFin = DateTime::createFromFormat('Y-m-d', $data->dateFin);

                        $booking->setDateFin($dateFin);
                        $booking->getAppels()->setDateFin($dateFin);
                    }
                }
            }

            if (null !== $booking->getChantier()) {

                $dateDebut = DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $data->dateDebut);
                $dateDebut->setTime(0, 0, 0);

                $booking->setDateDebut($dateDebut);
                $booking->getChantier()->setDateDebut($dateDebut);

                if (isset($data->dateFin) && !empty($data->dateFin)) {

                    $dateFin = DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $data->dateFin);
                    $dateFin->setTime(0, 0, 0);

                    $booking->setDateFin($dateFin);
                    $booking->getChantier()->setDateFin($dateFin);
                }

                $em->persist($booking->getChantier());
                $em->flush($booking->getChantier());
            } else {
                // On met la dateFin à null s'il n'y a pas de donnée, sinon on insert la donnée dateFin
                if (isset($data->allDay)) {
                    if ($data->allDay) {
                        $booking->setAllDay($data->allDay);
                        $booking->setDateFin(null);
                    } else {
                        $booking->setAllDay($data->allDay);
                        $dateFin = new DateTime($data->dateDebut);
                        $dateFin->modify('+1 hour');
                        if (null === $booking->getChantier()) {
                            $booking->setDateFin($dateFin);
                        }
                    }
                }
                if (isset($data->dateFin) && !empty($data->dateFin)) {
                    $booking->setDateFin(new DateTime($data->dateFin));

                    if (null !== $booking->getChantier()) {
                        $dateFin = DateTime::createFromFormat('Y-m-d', $data->dateFin);

                        $booking->setDateFin($dateFin);
                        $booking->getChantier()->setDateFin($dateFin);
                    }
                }
            }
            $em->persist($booking);
            $em->flush($booking);

            // On rerourne le code
            return new Response('Ok', $code);
        } else {
            // Les données sont incomplètes
            return new Response('Données incomplète', 404);
        }
    }
}
