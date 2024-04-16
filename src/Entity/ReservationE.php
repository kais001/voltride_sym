<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationERepository; 
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ReservationERepository::class)]
class ReservationE
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_e", type: "integer", nullable: false)]
    private ?int $id_e = null;

   
    #[ORM\Column(name: "nbrPersonne", type: "integer" )]
    #[Assert\PositiveOrZero(message: "Le nombre de personnes doit être un nombre positif ou zéro.")]
    private ?int $nbrPersonne = null;

    #[ORM\Column(name: "Commentaire", type: "string", length: 15)]
    #[Assert\NotBlank(message: "Le commentaire ne peut pas être vide.")]
    #[Assert\Length(max: 150, maxMessage: "Le commentaire ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $Commentaire = null;

    #[ORM\ManyToOne(targetEntity: Evenement::class)]
    #[ORM\JoinColumn(name: "id_event", referencedColumnName: "id_event")]
    private ?Evenement $evenement = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_u", referencedColumnName: "id_u")]
    private ?Utilisateur $utilisateur = null;

    public function getId_e(): ?int
    {
        return $this->id_e;
    }

    public function getNbrPersonne(): ?int
    {
        return $this->nbrPersonne;
    }

    public function setNbrPersonne(?int $nbrPersonne): static
    {
        $this->nbrPersonne = $nbrPersonne;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(?string $Commentaire): static
    {
        $this->Commentaire = $Commentaire;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): static
    {
        $this->evenement = $evenement;

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