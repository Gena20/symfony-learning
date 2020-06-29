<?php

namespace App\Service;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\Exception\StorageException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class MovieManager
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var MovieRepository
     */
    private MovieRepository $repo;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $em, MovieRepository $repo, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->logger = $logger;
    }

    /**
     * Get MovieRepository.
     *
     * @return MovieRepository
     */
    public function getRepository(): MovieRepository
    {
        return $this->repo;
    }

    /**
     * Get the movie by ID.
     *
     * @param int $id
     *
     * @return Movie|null
     */
    public function get(int $id): ?Movie
    {
        return $this->repo->find($id);
    }

    /**
     * Update the movie info.
     *
     * @param Movie $movie
     *
     * @return Movie
     *
     * @throws StorageException
     */
    public function update(Movie $movie): Movie
    {
        $movieId = $movie->getId();
        if ($movieId === null) {
            throw new StorageException("Movie with id($movieId) was not found.");
        }
        $this->flush();

        $this->logger->info('Movie was updated.', ['id' => $movie->getId()]);

        return $movie;
    }

    /**
     * Store a movie.
     *
     * @param Movie $movie
     *
     * @return Movie
     *
     * @throws StorageException
     */
    public function store(Movie $movie): Movie
    {
        $movieId = $movie->getId();
        if ($movieId !== null) {
            throw new StorageException("Movie with id($movieId) already exists.");
        }
        $this->em->persist($movie);
        $this->flush();

        $this->logger->info('Movie was stored.', ['id' => $movie->getId()]);

        return $movie;
    }

    /**
     * Delete the movie.
     *
     * @param Movie $movie
     *
     * @return Movie
     *
     * @throws StorageException
     */
    public function delete(Movie $movie): Movie
    {
        $movieId = $movie->getId();
        if ($movieId === null) {
            throw new StorageException("Movie with id($movieId) was not found.");
        }
        $this->em->remove($movie);
        $this->flush();

        $this->logger->info('Movie was deleted.', ['id' => $movie->getId()]);

        return $movie;
    }

    /**
     * Flush all changes.
     *
     * @throws StorageException
     */
    public function flush(): void
    {
        try {
            $this->em->flush();
        } catch (\Exception $ex) {
            throw new StorageException($ex->getMessage(), (int) $ex->getCode(), $ex);
        }
    }
}
