## Guide: Creating a Dynamic List in Symfony - Step-by-Step Tutorial

### Step 1: Create Controller and Template

Create a controller with a route annotation. For example, let's create an "ExampleController" with the route "/example".

```php
#[Route('/example', name: 'app_example')]
public function index(): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    return $this->render('example/index.html.twig', [
        'current_page' => 'app_example',
    ]);
}
```

Create a template file named "index.html.twig" under the "templates/example" directory. Use the following code as a starting point:

```twig
{% extends '_inc/listPage.html.twig' %}

{% block title %}Appels{% endblock %}

{% block list %}
    <div class="d-flex justify-content-center container mt-5 mb-5">
        <h1 class="font-weight-bold statut" style="font-size: 3em;">Les appels en cours</h1>
    </div>

    <div class="client-container border rounded container bg-light p-2"></div>
{% endblock %}
```

Ensure that the `<div class="client-container">` is present, as it will be used to dynamically populate the data later.

### Step 2: Create JavaScript File

Create a JavaScript file named "example.js" under the "assets/example" directory. This file will handle the dynamic population of the list and scroll functionality.

In the JavaScript file, implement the logic to detect when the scroll reaches the bottom of the page. Upon reaching the bottom, make an Ajax request to an appropriate Symfony controller (e.g., "AjaxExampleController").

The controller should fetch the required data from the database, convert it to JSON format, and return it as a response.

Once the response is received in the JavaScript file, generate the HTML code dynamically and append it to the `<div class="client-container">` element.

Here's an example:

```JavaScript
// get references to the loading element and the client list container
const loading = document.querySelector('.chargement');
const clientListContainer = document.querySelector('.client-container');

// get reference to the "Back to top" button and hide it initially
const backToTopButton = document.querySelector('.back-to-top');

// initialize offset and limit values
let offset = 0;
let url = '/ajax/example';
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
    fetch(url, {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json'
        },
        body: JSON.stringify({
        offset: offset,
        limit: limit,
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
  clientElement.classList.add('row', 'border', 'border-light', 'rounded', 'bg-client', 'm-2', 'p-2', 'client');
  clientElement.innerHTML = `
    <h1>                    ${client.nom}</h1>
    <div>Code d'exemple:    ${client.codeExample}</div>
  `;
  return clientElement;
}

// function to create modal when clicking on client
function createClientModal(client, clientElement) {
  clientElement.setAttribute('type', 'button');
  clientElement.setAttribute('data-bs-toggle', 'modal');
  clientElement.setAttribute('data-bs-target', `#clientModal-${client.codeExample}`);

  const clientModal = document.createElement('div');
  clientModal.classList.add('modal', 'fade', 'client');
  clientModal.id = `clientModal-${client.codeExample}`;
  clientModal.setAttribute('tabindex','-1');
  clientModal.setAttribute('aria-hidden','true');
  clientModal.setAttribute('aria-labelledby',`clientModalLabel-${client.codeExample}`);

  clientModal.innerHTML = 
  `
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="clientModalLabel-${client.codeExample}">${client.nom}</h1>
          <button type="button" class="btn-close border rounder bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Code Example:</strong>    ${client.codeExample}</p>
            </div>
              <div class="col-md-6">
                <p><strong>Nom:</strong>                ${client.nom}</p>
                <p><strong>Téléphone:</strong>          ${client.tel}</p>
                <p><strong>Adresse:</strong>            ${client.adr}<br>${client.cp} ${client.ville}</p>
              </div>
              <div class="col-md-6">
              </div>
              <div class="col-md-6">
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row w-100">
            <a href="/example/${client.codeExample}" class="btn btn-primary col p-2 m-2">Afficher</a>
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
```

This JavaScript exmaple code is long but it's commented. If you find it a bit too complicated, I would heavily encourage to look up videos and guides on how to make a dynamic list with infinite scroll using JS.

Here are a few guides that I personally followed:
[Learn Infinite Scroll in JavaScript](https://www.youtube.com/watch?v=xHm6AbNwAw8)
[ Symfony and Vue Tutorial: Infinite Scroll](https://www.youtube.com/watch?v=gJuf_LAlphA)

Keep in mind that this JS file doesn't ONLY handle generating the HTML code for each client. It also handles showing a button that appears on bottom right that's meant to take the user back to the top when clicked and also a small loading animation that appears when loading the clients. Which is why it's important to add the following to the template:In the template file "index.html.twig", add the following block to include the back-to-top button and loading animation:

```twig
{% block content %}
    <button class="btn btn-primary back-to-top">
        <i class="fa-solid fa-angles-up"></i>
        Retour en haut
    </button>
    {% include '_inc/chargementAnimation.html.twig' %}
{% endblock %}
```

### Step 3: Configure Webpack Encore

Open the "webpack.config.js" file located in the root directory of your Symfony project.

Add an entry for your new JavaScript file. For example:

```javascript
.addEntry('example_JS', './assets/example/example.js')
```

This entry ensures that the file will be processed by Webpack Encore.

Save the file and run the following command in the terminal to compile the assets:

```bash
npm run build
```

Alternatively, you can use `npm run watch` to automatically recompile the assets during development.

### Step 4: Add JavaScript to Template

Open the "index.html.twig" template file again.

Add the following block to include the JavaScript file:

```twig
{% block javascripts %}
    {{ parent() }}
    {{ encore_entry_script_tags('example_JS') }}
{% endblock %}
```

This will load the compiled JavaScript file into your template.

### Step 5: Create an Ajax Controller

Create a new controller called "AjaxExampleController" in the "Controller/Ajax" directory.

Implement a method that handles the Ajax request. For example:

```php
#[Route('/ajax/example', name: 'app_ajax_example', methods: ['POST'])]
public function getClientsExample(Request $request, ExampleRepository $exampleRepository)
{
    $data = json_decode($request->getContent(), true);
    $offset = $data['offset'] ?? 0;
    $limit = $data['limit'] ?? 10;

    $examples = $exampleRepository->findByLimit($offset, $limit);
    $examplesArray = $exampleRepository->collectionToArray($examples);
    $total = $exampleRepository->getCountExample();
    
    return $this->json([
    'clients' => $examplesArray,
    'total' => $total,
	]);
}
```

Make sure to adjust the code to match your entity and repository names, as well as the data you want to retrieve.

### Step 6: Implement Repository Functions

Inside the repository associated with your entity (e.g., "ExampleRepository"), implement the following functions:

```php
public function findByLimit(?int $offset = 0, int $limit = 10)
{
    // Implement the query to fetch data with the given offset and limit
}

public function collectionToArray(array $examples)
{
    // Convert the fetched entities to an array of required data
}

public function getCountExample()
{
    // Implement the query to get the total count of examples
}
```

These functions should retrieve the desired data from your database and format it appropriately for display.

## Final Steps

Test and debug your code to ensure it works as expected.

You can customize the template, JavaScript, and repository functions based on your specific requirements and entity structure.

By following these steps, you should be able to create a dynamic list of anything (e.g., clients, chantier, appels) in your Symfony project.