<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationBRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationBRepository::class)]
class ReservationB
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_r", type: "integer", nullable: false)]
    private ?int $idR = null;

    #[ORM\Column(name: "date_d", type: "date", nullable: true)]
    #[Assert\NotNull(message: "Please provide a start date.")]
    #[Assert\GreaterThanOrEqual(value: "today", message: "The start date must be today or in the future.")]
    #[Assert\LessThanOrEqual(propertyPath: "dateFin", message: "The start date must be on or before the end date.")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: "date_f", type: "date", nullable: true)]
    #[Assert\NotNull(message: "Please provide an end date.")]
    #[Assert\GreaterThanOrEqual(propertyPath: "dateDebut", message: "The end date must be after the start date.")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(name: "heure_d", type: "time", nullable: true)]
    #[Assert\NotNull(message: "Please provide a start time.")]
    private ?\DateTimeInterface $heureDebut = null;

    #[ORM\Column(name: "heure_f", type: "time", nullable: true)]
    #[Assert\NotNull(message: "Please provide an end time.")]
    #[Assert\GreaterThan(propertyPath: "heureDebut", message: "The end time must be after the start time.")]
    private ?\DateTimeInterface $heureFin = null;

    #[ORM\ManyToOne(targetEntity: Borne::class)]
    #[ORM\JoinColumn(name: "id_b", referencedColumnName: "id")]
    #[Assert\NotNull(message: "Please select a Borne.")]
    private ?Borne $borne = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_u", referencedColumnName: "id_u")]
    #[Assert\NotNull(message: "Please select a Utilisateur.")]
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

    
    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(?\DateTimeInterface $heureDebut): static
    {
        $this->heureDebut = $heureDebut;
        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(?\DateTimeInterface $heureFin): static
    {
        $this->heureFin = $heureFin;
        return $this;
    }

    public function getBorne(): ?Borne
    {
        return $this->borne;
    }

    public function setBorne(?Borne $borne): static
    {
        $this->borne = $borne;

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