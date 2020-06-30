<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Service\MovieManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
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
     * Get the movie by id.
     *
     * @Route("/api/movie/{id<\d+>?1}", name="api_show_movie", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the movie by id",
     *     @Model(type=Movie::class)
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="The field used to get the movie"
     * )
     * @SWG\Tag(name="get-movie")
     *
     * @param $id
     * @return Response
     */
    public function showMovie(int $id): Response
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
     * Deserialize the movie info.
     *
     * @Route("/api/deserialize-movie", name="deserialize_movie", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Deserializes the movie info and returns it",
     *     @Model(type=Movie::class)
     * )
     * @SWG\Parameter(
     *     name="json",
     *     in="query",
     *     type="string",
     *     description="The field used to get info about the movie"
     * )
     * @SWG\Tag(name="deserialize-movie")
     *
     * @param Request $request
     * @return Response
     */
    public function deserializeMovie(Request $request): Response
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
