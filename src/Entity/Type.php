<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "typeName", type: "string", length: 255)]
    private ?string $typeName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): static
    {
        $this->typeName = $typeName;

        return $this;
    }

    public function __toString(): string
    {
        return $this->typeName; // Retourne le nom du type lors de sa conversion en chaîne de caractères
    }
}
