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
     *
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

        return $this->json($data, $status);
    }

    /**
     * Delete the movie by id.
     *
     * @Route("/api/movie/{id<\d+>?1}", name="api_delete_movie", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete the movie by id",
     *     @Model(type=Movie::class)
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="The field used to delete the movie"
     * )
     * @SWG\Tag(name="delete-movie")
     *
     * @param $id
     *
     * @return Response
     */
    public function deleteMovie(int $id): Response
    {
        $movie = $this->movieManager->get($id);
        $data = sprintf('{"message": "Movie (id: %s) was not found."}', $id);
        $status = Response::HTTP_UNPROCESSABLE_ENTITY;

        if ($movie !== null) {
            $this->movieManager->delete($movie);
            $data = $this->serializer->serialize($movie, 'json', [
                'groups' => 'show',
            ]);
            $status = Response::HTTP_OK;
        }

        return $this->json($data, $status);
    }

    /**
     * Update the movie by id.
     *
     * @Route("/api/movie/{id<\d+>?1}", name="api_update_movie", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="Update the movie by id",
     *     @Model(type=Movie::class)
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="The field used to update the movie"
     * )
     * @SWG\Parameter(
     *     name="movie_info",
     *     in="body",
     *     @SWG\Schema(type="object",
     *         @SWG\Property(property="movie", ref=@Model(type=Movie::class))
     *     )
     * )
     * @SWG\Tag(name="delete-movie")
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function updateMovie(int $id, Request $request): Response
    {
        $movie = $this->movieManager->get($id);
        $data = sprintf('{"message": "Movie (id: %s) was not found."}', $id);
        $status = Response::HTTP_UNPROCESSABLE_ENTITY;

        if ($movie !== null) {
            $movieData = $this->serializer->deserialize($request->getContent(), Movie::class, 'json');
            $violations = $this->validator->validate($movieData);

            if ($violations->count() > 0) {
                $data = ['message' => $violations->get(0)->getMessage()];
                $status = Response::HTTP_BAD_REQUEST;
            } else {
                $this->movieManager->update($movieData);
                $data = $this->serializer->serialize($movieData, 'json', ['groups' => 'show']);
                $status = Response::HTTP_OK;
            }
        }

        return $this->json($data, $status);
    }

    /**
     * Create a movie.
     *
     * @Route("/api/movie/{id<\d+>?1}", name="api_create_movie", methods={"POST"})
     * @SWG\Response(
     *     response=201,
     *     description="Create a movie",
     *     @Model(type=Movie::class)
     * )
     * @SWG\Parameter(
     *     name="movie_info",
     *     in="body",
     *     @SWG\Schema(type="object",
     *         @SWG\Property(property="movie", ref=@Model(type=Movie::class))
     *     )
     * )
     * @SWG\Tag(name="delete-movie")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createMovie(Request $request): Response
    {
        $movieData = $this->serializer->deserialize($request->getContent(), Movie::class, 'json');
        $violations = $this->validator->validate($movieData);

        if ($violations->count() > 0) {
            $data = ['message' => $violations->get(0)->getMessage()];
            $status = Response::HTTP_BAD_REQUEST;
        } else {
            $movie = $this->movieManager->store($movieData);
            $data = $this->serializer->serialize($movie, 'json', ['groups' => 'show']);
            $status = Response::HTTP_CREATED;
        }

        return $this->json($data, $status);
    }

    /**
     * Deserialize the movie info.
     *
     * @Route("/api/deserialize-movie", name="deserialize_movie", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Deserializes the movie info and returns it",
     *     @Model(type=Movie::class)
     * )
     * @SWG\Parameter(
     *     name="movie_info",
     *     in="body",
     *     @SWG\Schema(type="object",
     *         @SWG\Property(property="movie", ref=@Model(type=Movie::class))
     *     )
     * )
     * @SWG\Tag(name="deserialize-movie")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function deserializeMovie(Request $request): Response
    {
        $movie = $this->serializer->deserialize($request->getContent(), Movie::class, 'json');
        $violations = $this->validator->validate($movie);

        if ($violations->count() > 0) {
            return $this->json(['message' => $violations->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($this->serializer->serialize($movie, 'json', ['groups' => 'show']), Response::HTTP_OK);
    }
}
