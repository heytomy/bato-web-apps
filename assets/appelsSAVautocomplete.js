import 'bootstrap';
import 'bootstrap-datepicker';

const $ = require('jquery');

var clientField = document.getElementById('appels_sav_client');

clientField.addEventListener('change', function() {

    var clientId = clientField.value;

    $.ajax({
        url: '/get-client-info/' + clientId,
        method: 'GET',
        success: function(response) {
            $('#appels_sav_Contrats').val(response.codecontrat);
            $('#appels_sav_Client').val(response.codeclient);
            $('#appels_sav_Nom').val(response.nom);
            $('#appels_sav_Adr').val(response.adr);
            $('#appels_sav_CP').val(response.cp);
            $('#appels_sav_Ville').val(response.ville);
            $('#appels_sav_Tel').val(response.tel);
            $('#appels_sav_Email').val(response.email);
        }, 
        error: function(jqXHR, textStatus, errorThrown) {
            var errorMessage = 'Une erreur s\'est produite. Veuillez réessayer. ';
            if (jqXHR.status && jqXHR.status == 404) {
                errorMessage += 'La ressource demandée n\'a pas été trouvée.';
            } else if (textStatus === 'timeout') {
                errorMessage += 'La requête a expiré. Veuillez réessayer plus tard.';
            } else if (textStatus === 'parsererror') {
                errorMessage += 'Une erreur s\'est produite lors de l\'analyse de la réponse. Veuillez réessayer.';
            } else {
                errorMessage += 'Une erreur s\'est produite lors du traitement de votre demande. Veuillez réessayer.';
            }
            alert(errorMessage);
        }
        
    });
});

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
  todayHighlight: true,
  language: 'fr',
});

