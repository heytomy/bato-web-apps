// get references to the loading element and the client list container
const loading = document.querySelector('.chargement');
const clientListContainer = document.querySelector('.client-container');

// get reference to the "Back to top" button and hide it initially
const backToTopButton = document.querySelector('.back-to-top');

// initialize offset and limit values
let offset = 0;
const limit = 10;

// initialize variable to track whether a request is in progress
let isFetching = false;


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
    fetch('/ajax/sav', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json'
        },
        body: JSON.stringify({
        offset: offset,
        limit: limit
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
  clientElement.classList.add('row', 'border', 'border-light', 'rounded', 'bg-client', 'm-2', 'p-2');
  clientElement.innerHTML = `
    <h1>                    ${client.nom}</h1>
    <div>Code du contrat:    ${client.codeContrat}</div>
    <div>Code du client:   ${client.codeClient}</div>
  `;
  return clientElement;
}

// function to create modal when clicking on client
function createClientModal(client, clientElement) {
  clientElement.setAttribute('type', 'button');
  clientElement.setAttribute('data-bs-toggle', 'modal');
  clientElement.setAttribute('data-bs-target', `#clientModal-${client.codeContrat}`);

  const clientModal = document.createElement('div');
  clientModal.classList.add('modal', 'fade');
  clientModal.id = `clientModal-${client.codeContrat}`;
  clientModal.setAttribute('tabindex','-1');
  clientModal.setAttribute('aria-hidden','true');
  clientModal.setAttribute('aria-labelledby',`clientModalLabel-${client.codeContrat}`);

  clientModal.innerHTML = 
  `
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="clientModalLabel-${client.codeContrat}">${client.nom}</h1>
          <button type="button" class="btn-close border rounder bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div>Code du contrat:    ${client.codeContrat}</div>
          <div>Code du client:   ${client.codeClient}</div>
          </br>
          <div>Adresse:           ${client.adr}</div>
          <div>CP:                ${client.cp}</div>
          </br></br></br>
        </div>
        <div class="modal-footer">
          <div class="row w-100">
            <a href="/sav/${client.codeContrat}" class="btn btn-primary col p-2 m-2">Afficher</a>
            <a href"#" class="btn btn-secondary col p-2 m-2" data-bs-dismiss="modal">Fermer</a>
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