<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PuzzleRepository")
 */
class Puzzle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"category_get_puzzle", "world_get_puzzle", "puzzle_show", "user_show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Groups({"category_get_puzzle", "world_get_puzzle", "puzzle_show", "user_show"})
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"category_get_puzzle", "world_get_puzzle", "puzzle_show", "user_show"})
     * @Assert\File(
     * maxSize = "1024k", 
     * mimeTypes={ "image/gif", "image/jpeg", "image/png", "image/svg+xml" },
     * mimeTypesMessage = "Please valid image format : gif, png, jpeg, svg"
     * )
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"category_get_puzzle", "world_get_puzzle", "puzzle_show", "user_show"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="puzzles")
     * @Groups({"world_get_puzzle"})
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\World", inversedBy="puzzles")
     * @Groups({"category_get_puzzle"})
     */
    private $world;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MakePuzzle", mappedBy="puzzle", orphanRemoval=true)
     */
    private $makePuzzles;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->makePuzzles = new ArrayCollection();
        $this->created_at = new \DateTime();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function setWorld(?World $world): self
    {
        $this->world = $world;

        return $this;
    }

    /**
     * @return Collection|MakePuzzle[]
     */
    public function getMakePuzzles(): Collection
    {
        return $this->makePuzzles;
    }

    public function addMakePuzzle(MakePuzzle $makePuzzle): self
    {
        if (!$this->makePuzzles->contains($makePuzzle)) {
            $this->makePuzzles[] = $makePuzzle;
            $makePuzzle->setPuzzle($this);
        }

        return $this;
    }

    public function removeMakePuzzle(MakePuzzle $makePuzzle): self
    {
        if ($this->makePuzzles->contains($makePuzzle)) {
            $this->makePuzzles->removeElement($makePuzzle);
            // set the owning side to null (unless already changed)
            if ($makePuzzle->getPuzzle() === $this) {
                $makePuzzle->setPuzzle(null);
            }
        }

        return $this;
    }
}
