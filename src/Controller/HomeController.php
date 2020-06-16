<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * YAML route.
     *
     * @return Response
     */
    public function indexYaml(): Response
    {
        return $this->render('output.html.twig', ['output' => 'YAML']);
    }
}
