import { Calendar } from "@fullcalendar/core";
import listPlugin from "@fullcalendar/list";
import frLocale from '@fullcalendar/core/locales/fr';
import moment from 'moment';

import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'

document.addEventListener("DOMContentLoaded", () => {
    let calendarElToday = document.getElementById("calendar-holder-dashboard-0");
    let calendarEls = document.querySelectorAll(".calendar-holder");
    let userId = calendarElToday.dataset.userId;

    console.log(userId);

    let { eventsUrl } = calendarElToday.dataset;

    const currentDate = new Date();

    // Create an array of dates for the current week
    const weekDates = Array.from({ length: 5 }, (_, i) => {
      const date = new Date(currentDate);
      date.setDate(currentDate.getDate() + i);
      return date;
    });

    // Format the dates as strings (optional)
    const weekDateStrings = weekDates.map(date => date.toDateString());

    // Log the array of dates
    // console.log(weekDates);
    calendarEls.forEach((calendarEl, index) => {
      let date = weekDates[index];
  
      // console.log({date, index});
      let calendar = new Calendar(calendarEl, {
        eventSources: [
          {
            url: eventsUrl,
            method: "POST",
            extraParams: {
              filters: JSON.stringify({
                userId: userId, 
                "calendar-id": "dashboard-view",
                dateDebut: moment(date).format('YYYY-MM-DD'), // filter events by date
                dateFin: moment(date).endOf('day').format('YYYY-MM-DDTHH:mm:ss'), // filter events by date
              })
            },
            failure: () => {
               alert("Il y avait une erreur pendant le fetching de FullCalendar!");
            },
          },
        ],
        initialDate: date,
        height: 200,
        headerToolbar: {
          left: "",
          center: "title",
          right: ""
        },
        locale: frLocale,
        initialView: "listDay",
        navLinks: true, // can click day/week names to navigate views
        plugins: [listPlugin],
        timeZone: "Europe/Paris",
        businessHours: { 
            startTime: '06:00',
            endTime: '20:00'
        },
        buttonText: {
          listWeek:     'Semaine',
          listDay:      'Jour'
        },
        views: {
          list: { // name of view
            titleFormat: { year: 'numeric', month: 'short', day: '2-digit' }
            // other view-specific options here
          }
        }
      });  
      calendar.render();
    });
  });