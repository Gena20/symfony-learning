<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    /**
     * Total amount of movie records.
     */
    private const RECORD_AMOUNT = 300;

    /**
     * @var Generator
     */
    private Generator $faker;

    /**
     * Raw data of genres.
     *
     * @var array
     */
    private static array $rawGenres = [
        'Action',
        'Adventure',
        'Comedy',
        'Crime',
        'Drama',
        'Fantasy',
        'Historical',
        'Historical fiction',
        'Horror',
        'Magical realism',
        'Mystery',
        'Paranoid fiction',
        'Philosophical',
        'Political',
        'Romance',
        'Saga',
        'Satire',
        'Science fiction',
        'Social',
        'Speculative',
        'Thriller',
        'Urban',
        'Western',
    ];

    /**
     * Raw data of keywords.
     *
     * @var array
     */
    private static array $rawKeywords = [
        'paint',
        'gallery',
        'at',
        'print',
        'pop art',
        'abstract',
        'artist',
        'wall art',
        'metropolitan museum of art',
        'abstract art',
        'acrylic paint',
        'sculpture',
        'watercolor',
        'art gallery',
        'oil painting',
        'art supplies',
        'watercolor painting',
        'modern art',
        'artwork',
        'fine arts',
        'paint online',
        'wall painting',
        'famous artists',
    ];

    private array $genres;

    private array $keywords;

    /**
     * AppFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->makeGenres($manager);
        $this->makeKeywords($manager);

        for ($i = 0; $i < self::RECORD_AMOUNT; ++$i) {
            $movie = (new Movie())
                ->setTitle($this->faker->title())
                ->setBudget($this->faker->randomNumber(9))
                ->setOverview($this->faker->text())
                ->setReleaseDate($this->faker->dateTime())
                ->setRuntime($this->faker->randomNumber(3));

            $this->addRandomGenres($movie);
            $this->addRandomKeywords($movie);

            $manager->persist($movie);

            if ($i % 100 === 0) {
                $manager->flush();
            }
        }

        $manager->flush();
    }

    private function addRandomGenres(Movie $movie): void
    {
        $amount = random_int(1, 4);
        for ($i = 0; $i < $amount; ++$i) {
            $genre = $this->faker->randomElement($this->genres);
            $movie->addGenre($genre);
        }
    }

    private function addRandomKeywords(Movie $movie): void
    {
        $amount = random_int(1, 6);
        for ($i = 0; $i < $amount; ++$i) {
            $keyword = $this->faker->randomElement($this->keywords);
            $movie->addGenre($keyword);
        }
    }

    private function makeGenres(ObjectManager $manager): void
    {
        foreach (self::$rawGenres as $rawGenre) {
            $genre = (new Genre())->setName($rawGenre);

            $manager->persist($genre);
            $this->genres[] = $genre;
        }

        $manager->flush();
    }

    private function makeKeywords(ObjectManager $manager): void
    {
        foreach (self::$rawKeywords as $rawKeyword) {
            $keyword = (new Genre())->setName($rawKeyword);

            $manager->persist($keyword);
            $this->keywords[] = $keyword;
        }

        $manager->flush();
    }
}
