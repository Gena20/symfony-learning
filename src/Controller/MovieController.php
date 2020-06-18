<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private MovieRepository $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @Route("/movie/{page<\d+>?1}", name="movie")
     * @param $page
     * @return Response
     */
    public function index($page): Response
    {
        $limit = 10;
        $movies = $this->movieRepository->getAll($page, $limit);
        $maxPages = ceil($movies->count() / $limit);
        return $this->render('movie/index.html.twig', compact('movies', 'maxPages', 'page'));
    }


}
