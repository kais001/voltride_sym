<?php
// Entité Admin
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Admin
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'id_u', referencedColumnName: 'id_u')]
    private ?Utilisateur $utilisateur;

    #[ORM\Column(length: 100)]
    private ?string $departement;

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(?string $departement): self
    {
        $this->departement = $departement;
        return $this;
    }
}
