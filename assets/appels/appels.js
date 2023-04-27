// get references to the loading element and the client list container
const loading = document.querySelector('.chargement');
const clientListContainer = document.querySelector('.client-container');

// get reference to the "Back to top" button and hide it initially
const backToTopButton = document.querySelector('.back-to-top');

// initialize offset and limit values
let offset = 0;
let url = '/ajax/appels';
const limit = 10;

// initialize variable to track whether a request is in progress
let isFetching = false;
let inputValue = '';


// add event listener to hide the "Back to top" button when the user scrolls back to the top of the page
window.addEventListener('scroll', function() {
    if (document.documentElement.scrollTop > 500) {
        backToTopButton.classList.add('show');
    } else {
        backToTopButton.classList.remove('show');
    }
  });
// function to fetch clients from the server and append them to the list
function fetchClients() {

    // if a request is already in progress, return immediately
    if (isFetching) {
        return;
    }
    // show loading animation
    afficheChargement();

    // make a POST request to the server to get the clients
    fetch(url, {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json'
        },
        body: JSON.stringify({
        offset: offset,
        limit: limit,
        search: inputValue
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        // hide loading animation
        cacheChargement();

        // append new clients to the list
        data.clients.forEach(client => {
        const clientElement = createClientElement(client);
        const clientModal = createClientModal(client, clientElement);
        
        clientListContainer.appendChild(clientElement);
        clientListContainer.appendChild(clientModal);
        });

        // update offset value for the next request
        offset += data.clients.length;

        // disable infinite scrolling if we have loaded all clients
        if (offset >= data.total) {
        window.removeEventListener('scroll', infiniteScrollHandler);
        }

        // show or hide the "Back to top" button based on the scroll position
        if (document.documentElement.scrollTop > 500) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }

        // set requestInProgress variable back to false
        isFetching = false;
    })
    .catch(error => {
        alert('il y avait une erreur pendant le fetching des clients');
        console.error(error);
        // hide loading animation
        cacheChargement();

        isFetching = false;
    });

    // set requestInProgress variable to true
    isFetching = true;
}

// function to handle infinite scrolling
function infiniteScrollHandler() {
  const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
  const offsetThreshold = 255;

  if ((clientHeight + scrollTop) >= (scrollHeight - offsetThreshold)) {
    fetchClients();
  }
}

// function to create a new client element
function createClientElement(client) {
  const clientElement = document.createElement('div');
  clientElement.classList.add('row', 'border', 'border-light', 'rounded', 'bg-client', 'm-2', 'p-2', 'client');
  clientElement.innerHTML = `
    <h1>                        ${client.nom}</h1>
    <div>${client.codeContrat ? '<strong>Code Contrat:</strong> ' + client.codeContrat : 'N\'a pas de contrat d\'entretien'}</div>
    <div>${client.codeClient ? '<strong>Code Client:</strong> ' + client.codeClient : '<strong style="color: #B60E0E;">Nouveau client !</strong>'}</div>
  `;
  return clientElement;
}

// function to create modal when clicking on client
function createClientModal(client, clientElement) {
  clientElement.setAttribute('type', 'button');
  clientElement.setAttribute('data-bs-toggle', 'modal');
  clientElement.setAttribute('data-bs-target', `#clientModal-${client.codeAppel}`);

  const clientModal = document.createElement('div');
  clientModal.classList.add('modal', 'fade', 'client');
  clientModal.id = `clientModal-${client.codeAppel}`;
  clientModal.setAttribute('tabindex','-1');
  clientModal.setAttribute('aria-hidden','true');
  clientModal.setAttribute('aria-labelledby',`clientModalLabel-${client.codeAppel}`);

  const dateFormatOptions = { day: 'numeric', month: 'numeric', year: 'numeric' };
  const formattedDateDebut = new Date(client.dateDebut).toLocaleDateString('fr-FR', dateFormatOptions);
  const formattedDateFin = new Date(client.dateFin).toLocaleDateString('fr-FR', dateFormatOptions);

  clientModal.innerHTML = 
  `
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="clientModalLabel-${client.codeAppel}">${client.nom}</h1>
          <button type="button" class="btn-close border rounder bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <p>${client.codeContrat ? '<strong>Code Contrat:</strong> ' + client.codeContrat : 'N\'a pas de contrat d\'entretien'}</p>
              <p>${client.codeClient ? '<strong>Code Client:</strong> ' + client.codeClient : '<strong style="color: #B60E0E;">Nouveau client !</strong>'}</p>
            </div>
              <div class="col-md-6">
                <p><strong>Nom:</strong>                ${client.nom}</p>
                <p><strong>Téléphone:</strong>          ${client.tel}</p>
                <p><strong>Adresse:</strong>            ${client.adr}<br>${client.cp} ${client.ville}</p>
              </div>
              <div class="col-md-6">
                <p><strong>Date de rendez-vous:</strong>   ${formattedDateDebut}</p>
                <p>${client.dateFin ? '<strong>Fin prévu le:</strong>' + formattedDateFin : ''}</p>
              </div>
              <div class="col-md-6">
                <p><strong>Rendez-vous urgent ?</strong>      <br> ${client.isUrgent ? '<p>Oui</p>' : '<p">Non</p>'}</p>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row w-100">
            <a href="/appels/${client.codeAppel}" class="btn btn-primary col p-2 m-2">Afficher</a>
            <a href="#" class="btn btn-secondary col p-2 m-2" data-bs-dismiss="modal">Fermer</a>
          </div>
        </div>
      </div>
    </div>
  `
  return clientModal;
}

// function to show loading animation
function afficheChargement() {
  loading.classList.add('affiche');
}

// function to hide loading animation
function cacheChargement() {
  loading.classList.remove('affiche');
}

// function to scroll back to the top of the page
function backToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

// add event listener for infinite scrolling
window.addEventListener('scroll', infiniteScrollHandler);

// add event listener for the "Back to top" button
backToTopButton.addEventListener('click', backToTop)

// fetch initial set of clients
fetchClients();

// const form = document.querySelector('#search-form');
// form.addEventListener('submit', event => {
//   event.preventDefault();

//   removeElementsByClass('client');

//   inputValue = document.querySelector('#nom').value;
//   url = '/ajax/appels/search';

//   fetchClients();
// })

const statutHeader = document.querySelector('.statut');
const appelsEnCours = document.querySelector('#appelsEnCours');
appelsEnCours.addEventListener('click', event => {
  statutHeader.textContent = 'Les appels en cours';

  offset = 0;
  removeElementsByClass('client');
  url = '/ajax/appels';

  fetchClients();
})

const appelsTermine = document.querySelector('#appelsTermine');
appelsTermine.addEventListener('click', event => {
  statutHeader.textContent = 'Les appels archivés';

  offset = 0;
  removeElementsByClass('client');
  url = '/ajax/appels/termine';

  fetchClients();
})

function removeElementsByClass(className){
  const elements = document.getElementsByClassName(className);
  while(elements.length > 0){
      elements[0].parentNode.removeChild(elements[0]);
  }
}