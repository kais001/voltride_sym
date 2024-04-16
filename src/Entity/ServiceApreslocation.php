<?php

namespace App\Entity;

use App\Repository\ServiceApreslocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceApreslocationRepository::class)]
class ServiceApreslocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idservice", type: "integer")]
    private ?int $idservice = null;

    #[ORM\ManyToOne(targetEntity: Type::class)]
    #[ORM\JoinColumn(name: 'type', referencedColumnName: 'id')]
    private ?Type $type;

    #[ORM\Column(name: "technicien", type: "string", length: 255)]
    private ?string $technicien = null;

    #[ORM\Column(name: "description", type: "string", length: 255)]
    private ?string $description = null;

    #[ORM\Column(name: "statut", type: "string", length: 255)]
    private ?string $statut = null;

    #[ORM\Column(name: "cout", type: "float")]
    private ?float $cout = null;

    public function getIdservice(): ?int
    {
        return $this->idservice;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }
    
    public function setType(?Type $type): static
    {
        $this->type = $type;
    
        return $this;
    }

    public function getTechnicien(): ?string
    {
        return $this->technicien;
    }

    public function setTechnicien(string $technicien): static
    {
        $this->technicien = $technicien;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(float $cout): static
    {
        $this->cout = $cout;

        return $this;
    }
}

