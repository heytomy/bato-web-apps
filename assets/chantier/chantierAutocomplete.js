import 'bootstrap';

const $ = require('jquery');

var clientField = document.getElementById('chantier_apps_codeClient');

clientField.addEventListener('change', function() {

    var clientId = clientField.value;

    $('#clear-fields-btn').on("click", function() {
        $('#chantier_apps_codeClient').val('')
        $('#chantier_apps_adresse').val('');
        $('#chantier_apps_cp').val('');
        $('#chantier_apps_ville').val('');
        $('#chantier_apps_Tel').val('');
        $('#chantier_apps_Email').val('');
        $('#chantier_apps_description').val('');
    });

    if (!clientId) { // check if clientId is empty or null
        // Clear all other fields
        $('#chantier_apps_adresse').val('');
        $('#chantier_apps_cp').val('');
        $('#chantier_apps_ville').val('');
        $('#chantier_apps_Tel').val('');
        $('#chantier_apps_Email').val('');

        return; // exit the function early
    }

    // If clientId is not empty or null, continue with the ajax call
    $.ajax({
        url: '/get-client-and-contrats-info/' + clientId,
        method: 'GET',
        success: function(response) {
        $('#chantier_apps_adresse').val(response.adr);
        $('#chantier_apps_cp').val(response.cp);
        $('#chantier_apps_ville').val(response.ville);
        $('#chantier_apps_Tel').val(response.tel);
        $('#chantier_apps_Email').val(response.email);


        },
        error: function(jqXHR, textStatus, errorThrown) {
            var errorMessage = 'Une erreur s\'est produite. Veuillez réessayer. ';
            if (jqXHR.status && jqXHR.status == 404) {
                errorMessage += 'La ressource demandée n\'a pas été trouvée.';
            } else {
                errorMessage += 'Une erreur s\'est produite lors du traitement de votre demande. Veuillez réessayer.';
            }
            alert(errorMessage);
        }
    });
});
