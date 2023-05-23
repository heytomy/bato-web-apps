# Managing Templates in Symfony Project

In your Symfony project, the templates are organized and managed according to the following structure and guidelines:

## 1. Base template: 
The `base.html.twig` file serves as the foundation for the entire application. It contains the header, footer, and various blocks. Notably, the `block body` within the `<body>` tag defines the main content area. The JavaScript block is inside this block and has the `{{ encore_entry_script_tags('app') }}` command to ensure that the JavaScript code is included on every page.

## 2. Include folder: 
The `_inc/` folder, located within the templates directory, houses several reusable template files:

   - **chargementAnimation.html.twig**: A simple HTML code displaying three circles, used as a loading animation.

   - **flatpickr.html.twig**: A date picker component implemented using JavaScript.

   - **navbar.html.twig**: This file contains the navigation bar for the website and is imported by the base template.

   - **listPage.html.twig** : Designed to provide a consistent layout for various lists on the website (e.g., SAV, Appels, Chantier, Devis, Calendrier). It consists of several blocks:
     - **back_button block**: Used to position a button on the top left of the list page.
     - **new_button block**: Allows positioning a button next to the back_button block.
     - **search block**: Provides a search function, typically fixed on the top right.
     - **list block**: Where the list content is displayed.
     - **content block**: Positioned below the list block and intended for other potential content, although it may not be extensively utilized.

## 3. Module-specific folders: 
Each module, such as "example," has its own folder within the templates directory. This folder contains various Twig files for different functionalities, such as `show.html.twig`, `index.html.twig`, `new.html.twig`, and `edit.html.twig`. Additionally, the folder may contain templates related to deleting the module, changing status, or other specific functions.

   - Show template: The `show.html.twig`file within a module's folder may import templates from other folders. For example:

```twig
	{% include 'photoExample/photo.html.twig' %}
	{% include 'commentaireExample/comment.html.twig' %}
```
   This allows importing and displaying additional content related to photos or comments in the show view of the module.
   - Index template: The `index.html.twig` template is responsible for dynamically listing the modules in your Symfony project. It provides a flexible and reusable layout to display the module's data. This template follows the recommended structure and leverages the power of Twig for rendering dynamic content.

## 4. Comment and Photo folders: 
If a module involves comments or photos, dedicated folders named `commentaireExample` and `photoExample` are created, respectively.
   - **Comment template**: Within the `commentaireExample` folder, the `comment.html.twig` template handles both comments and replies. It utilizes a Bootstrap-based example to present the comment structure. Additionally, the folder contains templates for deleting comments and replies, which are imported within `comment.html.twig`. [Example of comments](https://mdbootstrap.com/docs/standard/extended/comments/)
   - **Photo template**: The `photoExample` folder includes the `photo.html.twig` template, which implements a carousel layout to display photos. Similarly, a delete form for the photo is included within the folder and imported into the `photo.html.twig` template. [Carousel on bootstrap](https://getbootstrap.com/docs/4.0/components/carousel/)

By adhering to these conventions, you can effectively manage and organize the templates in your Symfony project. These guidelines ensure consistency, reusability, and ease of maintenance across different modules and functionalities.

