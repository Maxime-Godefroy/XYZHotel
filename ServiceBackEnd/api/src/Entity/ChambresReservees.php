<?php

namespace App\Entity;

use App\Repository\ChambresReserveesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChambresReserveesRepository::class)]
class ChambresReservees
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservations $reservation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chambres $chambre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservation(): ?Reservations
    {
        return $this->reservation;
    }

    public function setReservation(?Reservations $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getChambre(): ?Chambres
    {
        return $this->chambre;
    }

    public function setChambre(?Chambres $chambre): static
    {
        $this->chambre = $chambre;

        return $this;
    }
}
