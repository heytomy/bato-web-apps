import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import frLocale from '@fullcalendar/core/locales/fr';

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
          filters: JSON.stringify({ "calendar-id": "calendar-view" })
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
    locale: frLocale,
    initialView: "timeGridWeek",
    editable: true,
    navLinks: true, // can click day/week names to navigate views
    plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
    timeZone: "Europe/Paris",
    eventResizableFromStart: true,
    businessHours: {
        daysOfWeek: [1,2,3,4,5],

        startTime: '06:00',
        endTime: '20:00'
    },
    themeSystem: 'standard',
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

  calendar.on('dateClick', (e) => {
    console.log(e);
  })

  calendar.render();
});