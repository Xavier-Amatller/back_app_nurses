<?php

namespace App\Entity;

use App\Repository\DiagnosticoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiagnosticoRepository::class)]
#[ORM\Table(name: "diagnosticos")]
class Diagnostico
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dia_diagnostico = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dia_motivo = null;

    #[ORM\OneToOne(mappedBy: 'dia_id', cascade: ['persist', 'remove'])]
    private ?Registro $registro = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiaDiagnostico(): ?string
    {
        return $this->dia_diagnostico;
    }

    public function setDiaDiagnostico(?string $dia_diagnostico): static
    {
        $this->dia_diagnostico = $dia_diagnostico;

        return $this;
    }

    public function getDiaMotivo(): ?string
    {
        return $this->dia_motivo;
    }

    public function setDiaMotivo(?string $dia_motivo): static
    {
        $this->dia_motivo = $dia_motivo;

        return $this;
    }

    public function getRegistro(): ?Registro
    {
        return $this->registro;
    }

    public function setRegistro(?Registro $registro): static
    {
        // unset the owning side of the relation if necessary
        if ($registro === null && $this->registro !== null) {
            $this->registro->setDiagnostico(null);
        }

        // set the owning side of the relation if necessary
        if ($registro !== null && $registro->getDiagnostico() !== $this) {
            $registro->setDiagnostico($this);
        }

        $this->registro = $registro;

        return $this;
    }
}
