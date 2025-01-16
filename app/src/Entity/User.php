<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Please enter your email')]
    #[Assert\Email(message: 'Please enter a valid email')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    //#[Assert\NotBlank(message: 'Please enter your password')]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Please enter your name')]
    #[Assert\Regex(pattern: '/\d/', message: 'Invalid name', match: false)]
    private ?string $name = null;



    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Please chose your gender')]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\OneToMany(mappedBy: 'userA', targetEntity: Swipe::class)]
    private Collection $swipesAsUserA;

    #[ORM\OneToMany(mappedBy: 'userB', targetEntity: Swipe::class)]
    private Collection $swipesAsUserB;

    #[ORM\OneToMany(mappedBy: 'userA', targetEntity: Pair::class)]
    private Collection $pairsAsUserA;

    #[ORM\OneToMany(mappedBy: 'userB', targetEntity: Pair::class)]
    private Collection $pairsAsUserB;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please choose your interests')]
    private ?string $interests = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please enter your age')]
    #[Assert\Regex(pattern: '/^[0-9]+$/', message: 'Please enter a valid age')]
    #[Assert\GreaterThanOrEqual(value: 18, message: 'You need to be 18 years old')]
    private ?string $age = null;

    #[ORM\OneToMany(mappedBy: 'sennder', targetEntity: Message::class)]
    private Collection $sentMessage;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Message::class)]
    private Collection $recivedMessage;

    public function __construct()
    {
        $this->swipesAsUserA = new ArrayCollection();
        $this->swipesAsUserB = new ArrayCollection();
        $this->pairsAsUserA = new ArrayCollection();
        $this->pairsAsUserB = new ArrayCollection();
        $this->sentMessage = new ArrayCollection();
        $this->recivedMessage = new ArrayCollection();
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return Collection<int, Swipe>
     */
    public function getSwipesAsUserA(): Collection
    {
        return $this->swipesAsUserA;
    }

    public function addSwipesAsUserA(Swipe $swipesAsUserA): self
    {
        if (!$this->swipesAsUserA->contains($swipesAsUserA)) {
            $this->swipesAsUserA->add($swipesAsUserA);
            $swipesAsUserA->setUserA($this);
        }

        return $this;
    }

    public function removeSwipesAsUserA(Swipe $swipesAsUserA): self
    {
        if ($this->swipesAsUserA->removeElement($swipesAsUserA)) {
            // set the owning side to null (unless already changed)
            if ($swipesAsUserA->getUserA() === $this) {
                $swipesAsUserA->setUserA(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Swipe>
     */
    public function getSwipesAsUserB(): Collection
    {
        return $this->swipesAsUserB;
    }

    public function addSwipesAsUserB(Swipe $swipesAsUserB): self
    {
        if (!$this->swipesAsUserB->contains($swipesAsUserB)) {
            $this->swipesAsUserB->add($swipesAsUserB);
            $swipesAsUserB->setUserB($this);
        }

        return $this;
    }

    public function removeSwipesAsUserB(Swipe $swipesAsUserB): self
    {
        if ($this->swipesAsUserB->removeElement($swipesAsUserB)) {
            // set the owning side to null (unless already changed)
            if ($swipesAsUserB->getUserB() === $this) {
                $swipesAsUserB->setUserB(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pair>
     */
    public function getPairsAsUserA(): Collection
    {
        return $this->pairsAsUserA;
    }

    public function addPairAsUserA(Pair $pairAsUserA): self
    {
        if (!$this->pairsAsUserA->contains($pairAsUserA)) {
            $this->pairsAsUserA->add($pairAsUserA);
            $pairAsUserA->setUserA($this);
        }

        return $this;
    }

    public function removePairAsUserA(Pair $pairAsUserA): self
    {
        if ($this->pairsAsUserA->removeElement($pairAsUserA)) {
            // set the owning side to null (unless already changed)
            if ($pairAsUserA->getUserA() === $this) {
                $pairAsUserA->setUserA(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pair>
     */
    public function getPairsAsUserB(): Collection
    {
        return $this->pairsAsUserB;
    }

    public function addPairsAsUserB(Pair $pairsAsUserB): self
    {
        if (!$this->pairsAsUserB->contains($pairsAsUserB)) {
            $this->pairsAsUserB->add($pairsAsUserB);
            $pairsAsUserB->setUserB($this);
        }

        return $this;
    }

    public function removePairsAsUserB(Pair $pairsAsUserB): self
    {
        if ($this->pairsAsUserB->removeElement($pairsAsUserB)) {
            // set the owning side to null (unless already changed)
            if ($pairsAsUserB->getUserB() === $this) {
                $pairsAsUserB->setUserB(null);
            }
        }

        return $this;
    }

    public function getInterests(): ?string
    {
        return $this->interests;
    }

    public function setInterests(string $interests): self
    {
        $this->interests = $interests;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSentMessage(): Collection
    {
        return $this->sentMessage;
    }

    public function addSentMessage(Message $sentMessage): self
    {
        if (!$this->sentMessage->contains($sentMessage)) {
            $this->sentMessage->add($sentMessage);
            $sentMessage->setSennder($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): self
    {
        if ($this->sentMessage->removeElement($sentMessage)) {
            // set the owning side to null (unless already changed)
            if ($sentMessage->getSennder() === $this) {
                $sentMessage->setSennder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getRecivedMessage(): Collection
    {
        return $this->recivedMessage;
    }

    public function addRecivedMessage(Message $recivedMessage): self
    {
        if (!$this->recivedMessage->contains($recivedMessage)) {
            $this->recivedMessage->add($recivedMessage);
            $recivedMessage->setReceiver($this);
        }

        return $this;
    }

    public function removeRecivedMessage(Message $recivedMessage): self
    {
        if ($this->recivedMessage->removeElement($recivedMessage)) {
            // set the owning side to null (unless already changed)
            if ($recivedMessage->getReceiver() === $this) {
                $recivedMessage->setReceiver(null);
            }
        }

        return $this;
    }
}
