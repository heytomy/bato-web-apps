import 'bootstrap';

const $ = require('jquery');

const isUrgent = document.getElementById('appels_isUrgent');
const status = document.getElementById('appels_status');

isUrgent.addEventListener('change', function() {
    if (this.checked) {
        status.classList.remove('d-none');
        select.required = true;
    } else {
        status.classList.add('d-none');
        select.required = false;
    }
});

var clientField = document.getElementById('appels_ClientList');

clientField.addEventListener('change', function() {

    var clientId = clientField.value;

    if (!clientId) { // check if clientId is empty or null
        // Set the Contrats select to null and make it readonly
        $('#appels_CodeContrat').val('').prop('readonly', true);

        // Clear all other fields
        $('#appels_CodeClient').val('');
        $('#appels_Nom').val('');
        $('#appels_Adr').val('');
        $('#appels_CP').val('');
        $('#appels_Ville').val('');
        $('#appels_Tel').val('');
        $('#appels_Email').val('');
        
        // Remove all options from the Contrats select
        $('#appels_CodeContrat').find('option').remove();
        // Add a disabled placeholder option to the Contrats select
        $('#appels_CodeContrat').append($('<option>', {
            value: '',
            text: 'Choisissez le client pour voir les contrats',
            disabled: true,
            selected: true
        }));
        return; // exit the function early
    }

    // If clientId is not empty or null, continue with the ajax call
    $.ajax({
        url: '/get-client-and-contrats-info/' + clientId,
        method: 'GET',
        success: function(response) {
            // enable the Contrats select
            $('#appels_CodeContrat').prop('readonly', false);

            $('#appels_CodeClient').val(response.codeclient);
            $('#appels_Nom').val(response.nom);
            $('#appels_Adr').val(response.adr);
            $('#appels_CP').val(response.cp);
            $('#appels_Ville').val(response.ville);
            $('#appels_Tel').val(response.tel);
            $('#appels_Email').val(response.email);

            // Remove all options from the Contrats select
            $('#appels_CodeContrat').find('option').remove();

            // Add a placeholder option to the Contrats select
            $('#appels_CodeContrat').append($('<option>', {
                value: '',
                text: 'Choisissez le contrat'
            }));

            // Add all the client's contrat options to the Contrats select
            $.each(response.contrats, function(index, contrat) {
                $('#appels_CodeContrat').append($('<option>', {
                    value: contrat.codecontrat,
                    text: contrat.codecontrat
                }));
            });
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


