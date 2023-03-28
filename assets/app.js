/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/global.scss';

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');
require('bootstrap');

var clientField = document.getElementById('appels_sav_client');

clientField.addEventListener('change', function() {

    var clientId = clientField.value;

    $.ajax({
        url: '/get-client-info/' + clientId,
        method: 'GET',
        success: function(response) {
            // Update the form fields with the client's information
            $('#appels_sav_Adr').val(response.adr);
            $('#appels_sav_CP').val(response.cp);
            $('#appels_sav_Ville').val(response.ville);
            $('#appels_sav_Tel').val(response.tel);
            $('#appels_sav_Email').val(response.email);
        }, 
        error: function() {
            alert('Une erreur s\'est produite. Veuillez réessayer plus tard.');
        }
    });
});

console.log(clientField)