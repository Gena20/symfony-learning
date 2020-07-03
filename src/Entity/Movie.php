<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"show"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     * @Groups({"show"})
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Groups({"show"})
     */
    private ?string $overview = null;

    /**
     * @ORM\Column(type="float")
     * @Groups({"show"})
     */
    private ?float $budget = null;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show"})
     */
    private ?int $runtime = null;

    /**
     * @ORM\Column(type="date")
     * @Groups({"show"})
     */
    private ?\DateTimeInterface $releaseDate = null;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
     * @Groups({"show"})
     */
    private Collection $genres;

    /**
     * @ORM\ManyToMany(targetEntity=Keyword::class, inversedBy="movies")
     * @Groups({"show"})
     */
    private Collection $keywords;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="movies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ownerUser;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->keywords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(int $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }

        return $this;
    }

    /**
     * @return Collection|Keyword[]
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): self
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords[] = $keyword;
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): self
    {
        if ($this->keywords->contains($keyword)) {
            $this->keywords->removeElement($keyword);
        }

        return $this;
    }

    public function getOwnerUser(): ?User
    {
        return $this->ownerUser;
    }

    public function setOwnerUser(?User $ownerUser): self
    {
        $this->ownerUser = $ownerUser;

        return $this;
    }
}
