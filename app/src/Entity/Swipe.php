<?php

namespace App\Entity;

use App\Repository\SwipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SwipeRepository::class)]
class Swipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $userA = null;

    #[ORM\Column(length: 255)]
    private ?string $userB = null;

    #[ORM\Column]
    private ?bool $action = null;

    #[ORM\ManyToOne(inversedBy: 'swipes')]
    private ?User $swipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserA(): ?string
    {
        return $this->userA;
    }

    public function setUserA(string $userA): self
    {
        $this->userA = $userA;

        return $this;
    }

    public function getUserB(): ?string
    {
        return $this->userB;
    }

    public function setUserB(string $userB): self
    {
        $this->userB = $userB;

        return $this;
    }

    public function isAction(): ?bool
    {
        return $this->action;
    }

    public function setAction(bool $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getSwipe(): ?User
    {
        return $this->swipe;
    }

    public function setSwipe(?User $swipe): self
    {
        $this->swipe = $swipe;

        return $this;
    }
}
