<?php

namespace App\Entity;

use App\Repository\DietaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DietaRepository::class)]
#[ORM\Table(name: "dietas")]
class Dieta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'die_id', cascade: ['persist', 'remove'])]
    private ?Registro $registro = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistro(): ?Registro
    {
        return $this->registro;
    }

    public function setRegistro(?Registro $registro): static
    {
        // unset the owning side of the relation if necessary
        if ($registro === null && $this->registro !== null) {
            $this->registro->setDieId(null);
        }

        // set the owning side of the relation if necessary
        if ($registro !== null && $registro->getDieId() !== $this) {
            $registro->setDieId($this);
        }

        $this->registro = $registro;

        return $this;
    }
}
