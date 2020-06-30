<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Service\MovieManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    private MovieManager $movieManager;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(MovieManager $movieManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->movieManager = $movieManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/movie/{id<\d+>?1}", name="api_show_movie", methods={"GET"})
     *
     * @param $id
     *
     * @return Response
     */
    public function show($id): Response
    {
        $movie = $this->movieManager->get($id);
        $data = sprintf('{"message": "Movie (id: %s) was not found."}', $id);
        $status = Response::HTTP_UNPROCESSABLE_ENTITY;

        if ($movie !== null) {
            $data = $this->serializer->serialize($movie, 'json', [
                'groups' => 'show',
            ]);
            $status = Response::HTTP_OK;
        }

        return new Response($data, $status, [
            'Content-Type' => 'json; charset=utf-8',
        ]);
    }

    /**
     * @Route("/api/unserialize_movie", name="unserialize_movie", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function unserializeMovie(Request $request): Response
    {
        $movie = $this->serializer->deserialize($request->get('json'), Movie::class, 'json');
        $violations = $this->validator->validate($movie);

        if ($violations->count() > 0) {
            return new Response(
                sprintf('{"message": "%s"', $violations->get(0)->getMessage()),
                Response::HTTP_BAD_REQUEST, [
                'Content-Type' => 'json; charset=utf-8',
            ]);
        }

        return new Response(
            $this->serializer->serialize($movie, 'json', ['groups' => 'show']),
            Response::HTTP_OK, [
                'Content-Type' => 'json; charset=utf-8',
            ]);
    }
}
