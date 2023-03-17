<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
    #[Route('/calendrier', name: 'app_calendar')]
    public function index(): Response
    {
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }

    #[Route('/events', name: 'app_calendar_events')]
    public function getCalendarEvents(): JsonResponse
    {
        // Get events from your database or another source
        $events = [
            [
                'title' => 'Event 1',
                'start' => '2023-03-20T09:00:00',
                'end' => '2023-03-20T10:00:00',
            ],
            [
                'title' => 'Event 2',
                'start' => '2023-03-21T14:00:00',
                'end' => '2023-03-21T15:00:00',
            ],
        ];

        return new JsonResponse($events);
    }

}
