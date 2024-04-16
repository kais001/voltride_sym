<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoitureRepository; 
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $idV = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message:"La marque est obligatoire")]
    #[Assert\Length(max: 10, maxMessage:"La marque ne peut pas dépasser {{ 10 }} caractères")]
    private ?string $marque = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message:"Le modèle est obligatoire")]
    #[Assert\Length(max: 150, maxMessage:"Le modèle ne peut pas dépasser {{ limit }} caractères")]
    private ?string $modele = null;

    #[ORM\Column(length: 150)]
    #[Assert\Length(max: 150, maxMessage:"L'état ne peut pas dépasser {{ limit }} caractères")]
    private ?string $etat = null;

    #[ORM\Column(type: "float")]
    #[Assert\NotBlank(message:"Le prix de location est obligatoire")]
    #[Assert\Type(type: "float", message:"Le prix de location doit être un nombre")]
    #[Assert\PositiveOrZero(message:"Le prix de location doit être positif ou nul")]
    private ?float $prixLocation = null;

    #[ORM\Column(type: "float")]
    #[Assert\NotBlank(message:"Le kilométrage est obligatoire")]
    #[Assert\Type(type: "float", message:"Le kilométrage doit être un nombre")]
    #[Assert\PositiveOrZero(message:"Le kilométrage doit être positif ou nul")]
    private ?float $kilometrage = null;

    #[ORM\Column(length: 150)]
    #[Assert\Url(message:"L'URL de l'image n'est pas valide")]
    private ?string $image = null;

    public function getIdV(): ?int
    {
        return $this->idV;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getPrixLocation(): ?float
    {
        return $this->prixLocation;
    }

    public function setPrixLocation(?float $prixLocation): self
    {
        $this->prixLocation = $prixLocation;

        return $this;
    }

    public function getKilometrage(): ?float
    {
        return $this->kilometrage;
    }

    public function setKilometrage(?float $kilometrage): self
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function __toString()
{
    return 'Marque: ' . $this->marque . ', Modèle: ' . $this->modele . ', État: ' . $this->etat . ', Prix de location: ' . $this->prixLocation . ', Kilométrage: ' . $this->kilometrage . ', Image: ' . $this->image;
}

}
