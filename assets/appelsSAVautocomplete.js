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
