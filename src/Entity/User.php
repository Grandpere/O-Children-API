<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Quizz")
     */
    private $quizz_bookmarks;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Puzzle")
     */
    private $puzzle_bookmarks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlayQuizz", mappedBy="user", orphanRemoval=true)
     */
    private $playQuizzs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MakePuzzle", mappedBy="user", orphanRemoval=true)
     */
    private $makePuzzles;

    public function __construct()
    {
        $this->quizz_bookmarks = new ArrayCollection();
        $this->puzzle_bookmarks = new ArrayCollection();
        $this->playQuizzs = new ArrayCollection();
        $this->makePuzzles = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->getRole()->getName()];
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

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

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

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
    public function getQuizzBookmarks(): Collection
    {
        return $this->quizz_bookmarks;
    }

    public function addQuizzBookmark(Quizz $quizzBookmark): self
    {
        if (!$this->quizz_bookmarks->contains($quizzBookmark)) {
            $this->quizz_bookmarks[] = $quizzBookmark;
        }

        return $this;
    }

    public function removeQuizzBookmark(Quizz $quizzBookmark): self
    {
        if ($this->quizz_bookmarks->contains($quizzBookmark)) {
            $this->quizz_bookmarks->removeElement($quizzBookmark);
        }

        return $this;
    }

    /**
     * @return Collection|Puzzle[]
     */
    public function getPuzzleBookmarks(): Collection
    {
        return $this->puzzle_bookmarks;
    }

    public function addPuzzleBookmark(Puzzle $puzzleBookmark): self
    {
        if (!$this->puzzle_bookmarks->contains($puzzleBookmark)) {
            $this->puzzle_bookmarks[] = $puzzleBookmark;
        }

        return $this;
    }

    public function removePuzzleBookmark(Puzzle $puzzleBookmark): self
    {
        if ($this->puzzle_bookmarks->contains($puzzleBookmark)) {
            $this->puzzle_bookmarks->removeElement($puzzleBookmark);
        }

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
            $playQuizz->setUser($this);
        }

        return $this;
    }

    public function removePlayQuizz(PlayQuizz $playQuizz): self
    {
        if ($this->playQuizzs->contains($playQuizz)) {
            $this->playQuizzs->removeElement($playQuizz);
            // set the owning side to null (unless already changed)
            if ($playQuizz->getUser() === $this) {
                $playQuizz->setUser(null);
            }
        }

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
            $makePuzzle->setUser($this);
        }

        return $this;
    }

    public function removeMakePuzzle(MakePuzzle $makePuzzle): self
    {
        if ($this->makePuzzles->contains($makePuzzle)) {
            $this->makePuzzles->removeElement($makePuzzle);
            // set the owning side to null (unless already changed)
            if ($makePuzzle->getUser() === $this) {
                $makePuzzle->setUser(null);
            }
        }

        return $this;
    }
}
