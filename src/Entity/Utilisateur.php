<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository; 

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_u", type: "integer")]
    private ?int $idU = null;

    #[ORM\Column(name: "cin", type: "integer", nullable: true)]
    private ?int $cin = null;

    #[ORM\Column(name: "nom", type: "string", length: 150, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(name: "prenom", type: "string", length: 150, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(name: "email", type: "string", length: 150, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(name: "mot_de_passe", type: "string", length: 150, nullable: true)]
    private ?string $motDePasse = null;

    #[ORM\Column(name: "date_inscription", type: "date")]
    private \DateTime $dateInscription;

    #[ORM\Column(name: "image", type: "string", length: 150, nullable: true)]
    private ?string $image;

    public function getIdU(): ?int
    {
        return $this->idU;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
    public function __toString()
{
    return 'ID: ' . $this->idU . ', Cin: ' . $this->cin . ', Nom: ' . $this->nom . ', Prénom: ' . $this->prenom . ', Email: ' . $this->email . ', Date d\'inscription: ' . $this->dateInscription->format('Y-m-d') . ', Image: ' . $this->image;
}

}
