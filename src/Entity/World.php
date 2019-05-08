<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorldRepository")
 */
class World
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"world_list", "world_get_quizz", "category_get_quizz"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"world_list", "world_get_quizz", "category_get_quizz"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"world_list", "world_get_quizz"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"world_list", "world_get_quizz"})
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quizz", mappedBy="world")
     * @Groups({"world_get_quizz"})
     */
    private $quizzs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Puzzle", mappedBy="world")
     */
    private $puzzles;

    public function __construct()
    {
        $this->quizzs = new ArrayCollection();
        $this->puzzles = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection|Quizz[]
     */
    public function getQuizzs(): Collection
    {
        return $this->quizzs;
    }

    public function addQuizz(Quizz $quizz): self
    {
        if (!$this->quizzs->contains($quizz)) {
            $this->quizzs[] = $quizz;
            $quizz->addWorld($this);
        }

        return $this;
    }

    public function removeQuizz(Quizz $quizz): self
    {
        if ($this->quizzs->contains($quizz)) {
            $this->quizzs->removeElement($quizz);
            $quizz->removeWorld($this);
        }

        return $this;
    }

    /**
     * @return Collection|Puzzle[]
     */
    public function getPuzzles(): Collection
    {
        return $this->puzzles;
    }

    public function addPuzzle(Puzzle $puzzle): self
    {
        if (!$this->puzzles->contains($puzzle)) {
            $this->puzzles[] = $puzzle;
            $puzzle->addWorld($this);
        }

        return $this;
    }

    public function removePuzzle(Puzzle $puzzle): self
    {
        if ($this->puzzles->contains($puzzle)) {
            $this->puzzles->removeElement($puzzle);
            $puzzle->removeWorld($this);
        }

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
}
