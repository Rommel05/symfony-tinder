<?php

namespace App\Entity;

use App\Repository\PairRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PairRepository::class)]
class Pair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $userA = null;

    #[ORM\Column(length: 255)]
    private ?string $userB = null;

    #[ORM\ManyToOne(inversedBy: 'pairs')]
    private ?User $pair = null;

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

    public function getPair(): ?User
    {
        return $this->pair;
    }

    public function setPair(?User $pair): self
    {
        $this->pair = $pair;

        return $this;
    }
}
