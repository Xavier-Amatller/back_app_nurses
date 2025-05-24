<?php

namespace App\Entity;

use App\Repository\MovilizacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovilizacionRepository::class)]
#[ORM\Table(name: "movilizaciones")]
class Movilizacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $mov_sedestacion = null;

    #[ORM\Column]
    private ?bool $mov_ajuda_deambulacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mov_ajuda_descripcion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mov_cambios = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $mov_decubitos = null;

    #[ORM\OneToOne(mappedBy: 'mov_id', cascade: ['persist', 'remove'])]
    private ?Registro $registro = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isMovSedestacion(): ?bool
    {
        return $this->mov_sedestacion;
    }

    public function setMovSedestacion(bool $mov_sedestacion): static
    {
        $this->mov_sedestacion = $mov_sedestacion;

        return $this;
    }

    public function isMovAjudaDeambulacion(): ?bool
    {
        return $this->mov_ajuda_deambulacion;
    }

    public function setMovAjudaDeambulacion(bool $mov_ajuda_deambulacion): static
    {
        $this->mov_ajuda_deambulacion = $mov_ajuda_deambulacion;

        return $this;
    }

    public function getMovAjudaDescripcion(): ?string
    {
        return $this->mov_ajuda_descripcion;
    }

    public function setMovAjudaDescripcion(?string $mov_ajuda_descripcion): static
    {
        $this->mov_ajuda_descripcion = $mov_ajuda_descripcion;

        return $this;
    }

    public function getMovCambios(): ?string
    {
        return $this->mov_cambios;
    }

    public function setMovCambios(?string $mov_cambios): static
    {
        $this->mov_cambios = $mov_cambios;

        return $this;
    }

    public function getMovDecubitos(): ?string
    {
        return $this->mov_decubitos;
    }

    public function setMovDecubitos(?string $mov_decubitos): static
    {
        $this->mov_decubitos = $mov_decubitos;

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
            $this->registro->setMovilizacion(null);
        }

        // set the owning side of the relation if necessary
        if ($registro !== null && $registro->getMovilizacion() !== $this) {
            $registro->setMovilizacion($this);
        }

        $this->registro = $registro;

        return $this;
    }
}
