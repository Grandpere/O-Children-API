<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizzRepository")
 */
class Quizz
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"world_get_quizz", "category_get_quizz", "quizz_show", "user_show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Groups({"world_get_quizz", "category_get_quizz", "quizz_show", "user_show"})
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"world_get_quizz", "category_get_quizz", "quizz_show", "user_show"})
     * @Assert\File(
     * maxSize = "1024k", 
     * mimeTypes={ "image/gif", "image/jpeg", "image/png", "image/svg+xml" },
     * mimeTypesMessage = "Please valid image format : gif, png, jpeg, svg"
     * )
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"world_get_quizz", "category_get_quizz", "quizz_show", "user_show"})
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
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="quizz", cascade="remove")
     * @Groups({"quizz_show"})
     */
    private $questions;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="quizzs")
     * @Groups({"world_get_quizz"})
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\World", inversedBy="quizzs")
     * @Groups({"category_get_quizz"})
     */
    private $world;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlayQuizz", mappedBy="quizz", orphanRemoval=true)
     */
    private $playQuizzs;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->playQuizzs = new ArrayCollection();
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
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuizz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getQuizz() === $this) {
                $question->setQuizz(null);
            }
        }

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
     * @return Collection|PlayQuizz[]
     */
    public function getPlayQuizzs(): Collection
    {
        return $this->playQuizzs;
    }

    public function addPlayQuizz(PlayQuizz $playQuizz): self
    {
        if (!$this->playQuizzs->contains($playQuizz)) {
            $this->playQuizzs[] = $playQuizz;
            $playQuizz->setQuizz($this);
        }

        return $this;
    }

    public function removePlayQuizz(PlayQuizz $playQuizz): self
    {
        if ($this->playQuizzs->contains($playQuizz)) {
            $this->playQuizzs->removeElement($playQuizz);
            // set the owning side to null (unless already changed)
            if ($playQuizz->getQuizz() === $this) {
                $playQuizz->setQuizz(null);
            }
        }

        return $this;
    }
}
