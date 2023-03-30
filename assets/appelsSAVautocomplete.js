const $ = require('jquery');

var clientField = document.getElementById('appels_sav_client');

clientField.addEventListener('change', function() {

    var clientId = clientField.value;

    $.ajax({
        url: '/get-client-info/' + clientId,
        method: 'GET',
        success: function(response) {
            $('#appels_sav_CodeContrat').val(response.codecontrat);
            $('#appels_sav_CodeClient').val(response.codeclient);
            $('#appels_sav_Adr').val(response.adr);
            $('#appels_sav_CP').val(response.cp);
            $('#appels_sav_Ville').val(response.ville);
            $('#appels_sav_Tel').val(response.tel);
            $('#appels_sav_Email').val(response.email);
        }, 
        error: function() {
            alert('Une erreur s\'est produite. Veuillez réessayer.');
        }
    });
});
