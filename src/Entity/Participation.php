<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column]
    private ?int $ids = null;

    #[ORM\Column]
    private ?int $nbr_de_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getIds(): ?int
    {
        return $this->ids;
    }

    public function setIds(int $ids): static
    {
        $this->ids = $ids;

        return $this;
    }

    public function getNbrDeParticipant(): ?int
    {
        return $this->nbr_de_participant;
    }

    public function setNbrDeParticipant(int $nbr_de_participant): static
    {
        $this->nbr_de_participant = $nbr_de_participant;

        return $this;
    }
}
