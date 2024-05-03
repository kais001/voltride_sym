<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BorneRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BorneRepository::class)]
class Borne
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(type: "string", length: 20, nullable: false)]
    #[Assert\NotBlank(message: "Please provide an emplacement.")]
    #[Assert\Length(max: 20, maxMessage: "Emplacement should not exceed {{ limit }} characters.")]
    private ?string $emplacement;

    #[ORM\Column(type: "integer", nullable: false)]
    #[Assert\NotNull(message: "Please provide a capacite.")]
    #[Assert\Range(min: 1, max: 3, notInRangeMessage: "Capacite must be between {{ min }} and {{ max }}.")]
    private ?int $capacite;

    #[ORM\Column(type: "string", length: 20, nullable: false)]
    #[Assert\NotBlank(message: "Please provide an etat.")]
    #[Assert\Choice(choices: ['Disponible', 'Indisponible'], message: "Etat must be either 'Disponible' or 'Indisponible'.")]
    private ?string $etat;

    #[ORM\Column(type: "date", nullable: true)]
    #[Assert\LessThanOrEqual("today", message: "DateInst must be less than or equal to today.")]
    private ?\DateTimeInterface $dateInst = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(?string $emplacement): self
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(?int $capacite): self
    {
        $this->capacite = $capacite;
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

    public function getDateInst(): ?\DateTimeInterface
    {
        return $this->dateInst;
    }

    public function setDateInst(?\DateTimeInterface $dateInst): self
    {
        $this->dateInst = $dateInst;
        return $this;
    }

    public function __toString()
    {
        return 'ID: ' . $this->id . ', emplacement: ' . $this->emplacement . ', capacité: ' . $this->capacite . ', état: ' . $this->etat . ', Date d\'installation: ' . $this->dateInst->format('Y-m-d');
    }
}

