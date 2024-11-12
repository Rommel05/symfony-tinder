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

    #[ORM\ManyToOne(inversedBy: 'pairsAsUserA')]
    private ?User $userA = null;

    #[ORM\ManyToOne(inversedBy: 'pairsAsUserB')]
    private ?User $userB = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserA(): ?User
    {
        return $this->userA;
    }

    public function setUserA(?User $userA): self
    {
        $this->userA = $userA;

        return $this;
    }

    public function getUserB(): ?User
    {
        return $this->userB;
    }

    public function setUserB(?User $userB): self
    {
        $this->userB = $userB;

        return $this;
    }
}
