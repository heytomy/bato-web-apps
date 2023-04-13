import 'bootstrap';

const $ = require('jquery');

const isUrgent = document.getElementById('appels_isUrgent');
const status = document.getElementById('appels_status');

isUrgent.addEventListener('change', function() {
    const select = status.querySelector('select'); // get the select element inside the status div
    if (this.checked) {
        status.classList.remove('d-none');
        select.required = true;
    } else {
        status.classList.add('d-none');
        select.required = false;
        select.value = ''; // reset the value of the select element to empty string
    }
});

var clientField = document.getElementById('appels_ClientList');

clientField.addEventListener('change', function() {

    var clientId = clientField.value;

    $('#clear-fields-btn').on("click", function() {
        $('#appels_ClientList').val('')
        $('#appels_CodeContrat').val('').prop('readonly', true);
        $('#appels_CodeClient').val('').prop('readonly', true);
        $('#appels_Nom').val('');
        $('#appels_Adr').val('');
        $('#appels_CP').val('');
        $('#appels_Ville').val('');
        $('#appels_Tel').val('');
        $('#appels_Email').val('');
        $('#appels_Description').val('');

        $('#appels_rdvDateTime').val('');
        $('#appels_rdvDateTimeFin').val('');
        $('#appels_allDay').prop('checked', false);
        $('#appels_isUrgent').prop('checked', false);
        $('#appels_status').addClass('d-none');
        $('#appels_status select').prop('required', false).val('');

    });

    if (!clientId) { // check if clientId is empty or null
        // Set the Contrats select to null and make it readonly
        $('#appels_CodeContrat').val('').prop('readonly', true);
        $('#appels_CodeClient').val('').prop('readonly', true);

        // Clear all other fields
        $('#appels_Nom').val('');
        $('#appels_Adr').val('');
        $('#appels_CP').val('');
        $('#appels_Ville').val('');
        $('#appels_Tel').val('');
        $('#appels_Email').val('');

        // Remove all options from the Contrats select
        $('#appels_CodeContrat').find('option').remove();
        $('#appels_CodeClient').find('option').remove();

        $('#appels_CodeContrat').append($('<option>', {
            value: '',
            text: 'Choisissez le client pour voir les contrats',
            disabled: true,
            selected: true
        }));

        $('#appels_CodeClient').append($('<option>', {
            value: '',
            text: 'Choisissez le client',
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

        // Set the CodeClient select to the selected client
        $('#appels_CodeClient').val(response.codeclient);

        // Clear all options from the CodeClient select except the selected client option
        $('#appels_CodeClient').find('option').each(function() {
            if ($(this).val() !== response.codeclient) {
                $(this).remove();
            }
        });

        // Add the selected client option to the CodeClient select if it doesn't exist
        if (!$('#appels_CodeClient').find('option[value="' + response.codeclient + '"]').length) {
            $('#appels_CodeClient').append($('<option>', {
                value: response.codeclient,
                text: response.codeclient
            }));
        }
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

$(document).on('DOMContentLoaded', function() {
  // Set the CodeClient select to null and make it readonly
  $("#appels_CodeContrat").find("option").remove();
  $("#appels_CodeClient").find("option").remove();

  // Add the empty option to the Contrats select
  $("#appels_CodeContrat").append(
    $("<option>", {
      value: "",
      text: "Choisissez le contrat",
      selected: true,
    })
  );

  // Add the empty option to the CodeClient select
  $("#appels_CodeClient").append(
    $("<option>", {
      value: "",
      text: "Choisissez le client",
      selected: true,
    })
  );

  // Attach a change event listener to the isNewClient checkbox
    $("#appels_isNewClient").on("change", function () {
        if ($(this).is(":checked")) {
          // If the checkbox is checked, hide the ClientList, CodeClient, and CodeContrat fields
          $("#appels_ClientList").hide();
          $('label[for="appels_ClientList"]').hide();
          $("#appels_CodeClient").hide();
          $('label[for="appels_CodeClient"]').hide();
          $("#appels_CodeContrat").hide();
          $('label[for="appels_CodeContrat"]').hide();
        } else {
          // If the checkbox is unchecked, show the ClientList field and hide the CodeClient and CodeContrat fields
          $("#appels_ClientList").show();
          $("#appels_CodeClient").show();
          $("#appels_CodeContrat").show();
        }
      });

});






