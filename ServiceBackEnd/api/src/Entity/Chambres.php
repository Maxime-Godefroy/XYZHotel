<?php

namespace App\Entity;

use App\Repository\ChambresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChambresRepository::class)]
class Chambres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $categorie = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix_nuit = null;

    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $caracteristiques = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPrixNuit(): ?string
    {
        return $this->prix_nuit;
    }

    public function setPrixNuit(string $prix_nuit): static
    {
        $this->prix_nuit = $prix_nuit;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getCaracteristiques(): ?string
    {
        return $this->caracteristiques;
    }

    public function setCaracteristiques(string $caracteristiques): static
    {
        $this->caracteristiques = $caracteristiques;

        return $this;
    }
}
