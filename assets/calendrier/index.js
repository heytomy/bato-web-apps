import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import frLocale from '@fullcalendar/core/locales/fr'

import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'

document.addEventListener("DOMContentLoaded", () => {
  let calendarEl = document.getElementById("calendar-holder");

  let { eventsUrl } = calendarEl.dataset;

  let calendar = new Calendar(calendarEl, {
    editable: true,
    eventSources: [
      {
        url: eventsUrl,
        method: "POST",
        extraParams: {
          filters: JSON.stringify({}) // pass your parameters to the subscriber
        },
        failure: () => {
           alert("Il y avait une erreur pendant le fetching de FullCalendar!");
        },
      },
    ],
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek"
    },
    buttonText: {
        today: 'Aujourd\'hui',
        month: 'Mois',
        week: 'Semaine',
        list: 'Liste',
        day: 'Jour',
    },
    locale: 'fr',
    initialView: "dayGridMonth",
    editable: true,
    navLinks: true, // can click day/week names to navigate views
    plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
    timeZone: "Europe/Paris",
    eventResizableFromStart: true,

    events: [
        {
            title  : 'event1',
            start  : '2023-03-30'
        },
        {
            title  : 'event2',
            start  : '2023-03-30',
            end    : '2023-03-31'
        },
        {
            title  : 'event3',
            start  : '2023-03-30T12:30:00',
            allDay : false // will make the time show
        }
        ]
  });

  calendar.on('eventChange', (e) => {
    let url = `/ajax/calendrier/${e.event.id}/edit`;
    let data = {
        'titre': e.event.title,
        'dateDebut': e.event.start,
        'dateFin': e.event.end,
        'allDay': e.event.allDay,
    }

    let xhr = new XMLHttpRequest;
    xhr.open('PUT', url);
    xhr.send(JSON.stringify(data));
  });

  calendar.render();
});