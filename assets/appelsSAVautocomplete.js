import 'bootstrap';

const $ = require('jquery');

var clientField = document.getElementById('appels_ClientList');

clientField.addEventListener('change', function() {

    var clientId = clientField.value;

    $.ajax({
        url: '/get-client-and-contrats-info/' + clientId,
        method: 'GET',
        success: function(response) {
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
