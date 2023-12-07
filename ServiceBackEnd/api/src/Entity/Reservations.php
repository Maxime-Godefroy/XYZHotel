<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Clients $client = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_checkin = null;

    #[ORM\Column]
    private ?int $nombre_nuits = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getDateCheckin(): ?\DateTimeInterface
    {
        return $this->date_checkin;
    }

    public function setDateCheckin(\DateTimeInterface $date_checkin): static
    {
        $this->date_checkin = $date_checkin;

        return $this;
    }

    public function getNombreNuits(): ?int
    {
        return $this->nombre_nuits;
    }

    public function setNombreNuits(int $nombre_nuits): static
    {
        $this->nombre_nuits = $nombre_nuits;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
