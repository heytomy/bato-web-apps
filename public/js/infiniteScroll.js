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
    fetch('/sav/ajax', {
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
        clientListContainer.appendChild(clientElement);
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
    <h1>Nom:                ${client.nom}</h1>
    <div>Code du client:    ${client.codeContrat}</div>
    <div>Code du contrat:   ${client.codeClient}</div>
    <div>Adresse:           ${client.adr}</div>
    <div>CP:                ${client.cp}</div>
  `;
  return clientElement;
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