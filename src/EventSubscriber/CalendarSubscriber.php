<?php

namespace App\EventSubscriber;


use DateTimeInterface;
use CalendarBundle\Entity\Event;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Event\CalendarEvent;
use App\Repository\CalendrierRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $calendrierRepository;
    private $router;

    public function __construct(
        CalendrierRepository $calendrierRepository,
        UrlGeneratorInterface $router,
    ) {
        $this->calendrierRepository = $calendrierRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

    
        switch($filters['calendar-id']) {
            case 'calendar-view':
                $this->fillCalendarForCalendarView($calendar, $start, $end, $filters);
                break;
            case 'dashboard-view':
                $this->fillCalendarForDashboardView($calendar, $start, $end, $filters);
                break;
        }
    }

    public function fillCalendarForCalendarView(CalendarEvent $calendar, DateTimeInterface $start, \DateTimeInterface $end, array $filters)
    {
        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $bookings = $this->calendrierRepository
            ->createQueryBuilder('booking')
            ->where('booking.dateDebut BETWEEN :start and :end OR booking.dateFin BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($bookings as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                $booking->getTitre(),
                $booking->getDateDebut(),
                $booking->getDateFin() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

             /**
              * 'backgroundColor' => '#26d4ae',
              * 'borderColor' => $booking->get,
              */
            
            if (null !== $booking->getChantier()) {
                $color = $booking->getChantier()->getIDUtilisateur()->getColorCode();

                $bookingEvent->setOptions([
                    'id' => $booking->getId(),
                    'allDay' => $booking->isAllDay(),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                ]);

                $bookingEvent->addOption(
                    'url',
                    $this->router->generate('app_chantier_show', [
                        'id' => $booking->getChantier()->getId(),
                    ])
                );  
            }
            
            if (null !== $booking->getAppels()) {
                $color = $booking->getAppels()->getIDUtilisateur()->getColorCode();

                $bookingEvent->setOptions([
                    'id' => $booking->getId(),
                    'allDay' => $booking->isAllDay(),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                ]);

                $bookingEvent->addOption(
                    'url',
                    $this->router->generate('app_appels_show', [
                        'id' => $booking->getAppels()->getId(),
                    ])
                );
            }

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($bookingEvent);
        }
    }
    
    public function fillCalendarForDashboardView(CalendarEvent $calendar, DateTimeInterface $start, \DateTimeInterface $end, array $filters)
    {
        $userId = $filters['userId'];

        $chantiers = $this->calendrierRepository
            ->createQueryBuilder('rdv')
            ->innerJoin('rdv.Chantier', 'ch')
            ->innerJoin('ch.ID_Utilisateur', 'u')
            ->andWhere('u.id = :userId')
            ->andWhere('rdv.dateDebut BETWEEN :start and :end OR rdv.dateFin BETWEEN :start and :end')
            ->setParameter('userId', $userId)
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
            ;

        $appels = $this->calendrierRepository
            ->createQueryBuilder('rdv')
            ->innerJoin('rdv.appels', 'a')
            ->innerJoin('a.ID_Utilisateur', 'u')
            ->andWhere('u.id = :userId')
            ->andWhere('rdv.dateDebut BETWEEN :start and :end OR rdv.dateFin BETWEEN :start and :end')
            ->setParameter('userId', $userId)
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
            ;
        $bookings = array_merge($appels, $chantiers);

        foreach ($bookings as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                $booking->getTitre(),
                $booking->getDateDebut(),
                $booking->getDateFin() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

             /**
              * 'backgroundColor' => '#26d4ae',
              * 'borderColor' => $booking->get,
              */
            
            if (null !== $booking->getChantier()) {
                $color = $booking->getChantier()->getIDUtilisateur()->getColorCode();

                $bookingEvent->setOptions([
                    'id' => $booking->getId(),
                    'allDay' => $booking->isAllDay(),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                ]);

                $bookingEvent->addOption(
                    'url',
                    $this->router->generate('app_chantier_show', [
                        'id' => $booking->getChantier()->getId(),
                    ])
                );  
            }
            
            if (null !== $booking->getAppels()) {
                $color = $booking->getAppels()->getIDUtilisateur()->getColorCode();

                $bookingEvent->setOptions([
                    'id' => $booking->getId(),
                    'allDay' => $booking->isAllDay(),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                ]);

                $bookingEvent->addOption(
                    'url',
                    $this->router->generate('app_appels_show', [
                        'id' => $booking->getAppels()->getId(),
                    ])
                );
            }

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($bookingEvent);
        }
    }
}