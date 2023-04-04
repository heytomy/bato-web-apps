import 'bootstrap';
import 'bootstrap-datepicker';

const $ = require('jquery');

$.fn.datepicker.dates['fr'] = {
    days: ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
    daysShort: ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'],
    daysMin: ['di', 'lu', 'ma', 'me', 'je', 've', 'sa'],
    months: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
    monthsShort: ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'],
    today: 'Aujourd\'hui',
    clear: 'Effacer',
    format: 'dd/mm/yyyy',
    weekStart: 1,
    monthsTitle: 'Mois',
    yearSelectTitle: 'Année',
    weekStart: 1
};

$(".datepicker").datepicker({
  isRTL: true,
  daysOfWeekDisabled: '06',
  format: 'dd/mm/yyyy',
  autoclose: true,
  todayBtn: true,
  todayHighlight: true,
  language: 'fr',
  pickerPosition: 'bottom-left'
});

