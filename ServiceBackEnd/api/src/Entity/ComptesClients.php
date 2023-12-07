<?php

namespace App\Entity;

use App\Repository\ComptesClientsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComptesClientsRepository::class)]
class ComptesClients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Clients $client = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $solde_portefeuille = null;

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

    public function getSoldePortefeuille(): ?string
    {
        return $this->solde_portefeuille;
    }

    public function setSoldePortefeuille(?string $solde_portefeuille): static
    {
        $this->solde_portefeuille = $solde_portefeuille;

        return $this;
    }
}
