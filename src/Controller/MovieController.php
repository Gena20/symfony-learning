<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Service\MovieManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private MovieManager $movieManager;

    public function __construct(MovieManager $movieManager)
    {
        $this->movieManager = $movieManager;
    }

    /**
     * @Route("/test/{id<\d+>}")
     *
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function test($id, Request $request): Response
    {
        $movie = $this->movieManager->get($id);

        return $this->json($movie);
    }

    /**
     * @Route("/movie/{page<\d+>?1}", name="movie")
     *
     * @param $page
     *
     * @return Response
     */
    public function index($page): Response
    {
        $limit = 10;
        $movies = $this->movieManager->getRepository()->getAll($page, $limit);
        $maxPages = ceil($movies->count() / $limit);

        return $this->render('movie/index.html.twig', compact('movies', 'maxPages', 'page'));
    }

    /**
     * @Route("/movie/update/{id<\d+>}", name="update_movie")
     *
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request): Response
    {
        $movie = $this->movieManager->get($id);
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $this->movieManager->update($movie);

            return $this->redirectToRoute('show_movie', ['id' => $movie->getId()]);
        }

        return $this->render('movie/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/movie/create", name="create_movie")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $this->movieManager->store($movie);

            return $this->redirectToRoute('show_movie', ['id' => $movie->getId()]);
        }

        return $this->render('movie/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/movie/show/{id<\d+>}", name="show_movie")
     *
     * @param $id
     *
     * @return Response
     */
    public function show($id): Response
    {
        $movie = $this->movieManager->get($id);

        return $this->render('movie/show.html.twig', compact('movie'));
    }
}
