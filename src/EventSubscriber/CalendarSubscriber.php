<?php

namespace App\EventSubscriber;


use CalendarBundle\Entity\Event;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Event\CalendarEvent;
use App\Repository\CalendrierRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $calendrierRepository;
    private $router;

    public function __construct(
        CalendrierRepository $calendrierRepository,
        UrlGeneratorInterface $router
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
            
            $bookingEvent->setOptions([
                'backgroundColor' => '#26d4ae',
                'borderColor' => '#26d4ae',
                'id' => $booking->getId(),
                'allDay' => $booking->isAllDay(),
            ]);
            if (null !== $booking->getChantier()) {
                $bookingEvent->addOption(
                    'url',
                    $this->router->generate('app_chantier_show', [
                        'id' => $booking->getChantier()->getId(),
                    ])
                );
            }
            if (null !== $booking->getAppels()) {
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
    // ...
}