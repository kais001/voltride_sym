<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenementRepository; 
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id_event;

    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(message: "Le type de l'événement ne peut pas être vide.")]
    #[Assert\Length(max: 20, maxMessage: "Le type de l'événement ne peut pas dépasser {{ limit }} caractères.")]
    private $type;

    #[ORM\Column(name: "adresseEvenement", length: 15)]
    #[Assert\NotBlank(message: "L'adresse de l'événement ne peut pas être vide.")]
    #[Assert\Length(max: 15, maxMessage: "L'adresse de l'événement ne peut pas dépasser {{ limit }} caractères.")]
    private $adresseEvenement;

    #[ORM\Column(name: "dateEvenement", type: 'date')]
    #[Assert\NotBlank(message: "La date de l'événement ne peut pas être vide.")]
    #[Assert\Type("\DateTimeInterface", message: "La date de l'événement doit être au format date.")]
    private $dateEvenement;

    #[ORM\Column(name: "placesDispo", type: 'integer')]
    #[Assert\NotBlank(message: "Le nombre de places disponibles ne peut pas être vide.")]
    #[Assert\PositiveOrZero(message: "Le nombre de places disponibles doit être un nombre positif ou zéro.")]
    private $placesDispo;
   
    public function getId_event(): ?int
    {
        return $this->id_event;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getadresseEvenement(): ?string
    {
        return $this->adresseEvenement;
    }

    public function setadresseEvenement(?string $adresseEvenement): self
    {
        $this->adresseEvenement = $adresseEvenement;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(?\DateTimeInterface $dateEvenement): self
    {
        $this->dateEvenement = $dateEvenement;
        return $this;
    }


    public function getplacesDispo(): ?int
    {
        return $this->placesDispo;
    }

    public function setplacesDispo(?int $placesDispo): self
    {
        $this->placesDispo = $placesDispo;

        return $this;
    }
    public function __toString()
    {
        return 'ID: ' . $this->id_event . ', type: ' . $this->type . ', adresseEvenement: ' . $this->adresseEvenement .  ', dateEvenement: ' . $this->dateEvenement->format('Y-m-d') . ', Place Disponible: ' . $this->placesDispo;
    }

  
}