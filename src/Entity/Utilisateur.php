<?php
// Entité Utilisateur
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Security\CustomPasswordEncoder;


use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_u;

    
    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Vous devez remplir tous les champs vides")]
    #[Assert\Regex(pattern: "/^\d{8}$/", message: "Le CIN doit contenir 8 chiffres")]
    private ?int $cin;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Vous devez remplir tous les champs vides")]
    #[Assert\Regex(pattern: "/^[A-Z][a-zA-Z]*$/", message: "Le nom doit commencer par une lettre majuscule")]
    private ?string $nom;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Vous devez remplir tous les champs vides")]
    #[Assert\Regex(pattern: "/^[A-Z][a-zA-Z]*$/", message: "Le prénom doit commencer par une lettre majuscule")]
    private ?string $prenom;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Vous devez remplir tous les champs vides")]
    #[Assert\Email(message: "Manquant @ ou .")]
    private ?string $email;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Vous devez remplir tous les champs vides")]
    private ?string $motDePasse;

    

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateInscription;



    // Autres propriétés de l'utilisateur
    public function __construct()
    {
        
    }

    public function getId_u(): ?int
    {
        return $this->id_u;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(?string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;
        return $this;
    }

    public function getCin(): ?int // Méthode d'accès pour la propriété cin
    {
        return $this->cin;
    }

    public function setCin(?int $cin): self // Méthode d'accès pour la propriété cin
    {
        $this->cin = $cin;
        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(?\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }

    public function __toString()
    {
        return 'ID: ' . $this->id_u . ', Cin: ' . $this->cin . ', Nom: ' . $this->nom . ', Prénom: ' . $this->prenom . ', Email: ' . $this->email;
    }

    
}
