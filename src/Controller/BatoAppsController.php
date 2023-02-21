<?php
// src/Controller/BatoAppsController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class BatoAppsController
{
    public function index(): Response
    {
        $name="Application web Bato";

        return new Response(
            '<html><body>Welcome to : '.$name.'</body></html>'
        );
    }
}