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

    /**
     * @Route("/indexAnnotation", name="annotation")
     *
     * @return Response
     */
    public function indexAnnotation(): Response
    {
        return $this->render('output.html.twig', ['output' => 'Annotation']);
    }

    /**
     * @Route("/cube/{num?}", name="cube", methods={"GET"})
     *
     * @param int $num
     *
     * @return Response
     */
    public function cube(?int $num): Response
    {
        return $this->render('output.html.twig', ['output' => $num ** 3]);
    }

    /**
     * @Route("/double/{num<\d+>?0}", name="double")
     *
     * @param int $num
     *
     * @return Response
     */
    public function double(int $num): Response
    {
        return $this->render('output.html.twig', ['output' => $num ** 2]);
    }
}
