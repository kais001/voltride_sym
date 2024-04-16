<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationVoitureRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationVoitureRepository::class)]
class ReservationVoiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_r", type: "integer", nullable: false)]
    private ?int $idR = null;

    #[ORM\Column(name: "date_debut", type: "date", nullable: true)]
    #[Assert\NotBlank(message:"La date de début est obligatoire.")]
    #[Assert\GreaterThanOrEqual(value: "today", message: "The start date must be today or in the future.")]
    #[Assert\LessThanOrEqual(propertyPath: "dateFin", message: "The start date must be on or before the end date.")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: "date_fin", type: "date", nullable: true)]
    #[Assert\NotBlank(message:"La date de fin est obligatoire.")]
    #[Assert\GreaterThanOrEqual(propertyPath: "dateDebut", message: "The end date must be after the start date.")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(targetEntity: Voiture::class)]
    #[ORM\JoinColumn(name: "id_v", referencedColumnName: "id_v")]
    private ?Voiture $voiture = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_u", referencedColumnName: "id_u")]
    private ?Utilisateur $utilisateur = null;

    public function getIdR(): ?int
    {
        return $this->idR;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): static
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
