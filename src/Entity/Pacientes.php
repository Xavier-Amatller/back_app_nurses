<?php

namespace App\Entity;

use App\Repository\PacientesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PacientesRepository::class)]
class Pacientes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, unique: true)]
    private ?int $Pac_NumHistorial = null;

    #[ORM\Column(length: 50)]
    private ?string $Pac_Nombre = null;

    #[ORM\Column(length: 150)]
    private ?string $Pac_Apellidos = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Pac_Fecha_Nacimiento = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Pac_Direccion_Completa = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $Pac_Lengua_Materna = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Pac_Antecedentes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Pac_Alergias = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $Pac_Nombre_Cuidador = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $Pac_Telefono_Cuidador = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Pac_Fecha_Ingreso = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Pac_Timestamp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPacNumHistorial(): ?int
    {
        return $this->Pac_NumHistorial;
    }

    public function setPacNumHistorial(int $Pac_NumHistorial): static
    {
        $this->Pac_NumHistorial = $Pac_NumHistorial;

        return $this;
    }

    public function getPacNombre(): ?string
    {
        return $this->Pac_Nombre;
    }

    public function setPacNombre(string $Pac_Nombre): static
    {
        $this->Pac_Nombre = $Pac_Nombre;

        return $this;
    }

    public function getPacApellidos(): ?string
    {
        return $this->Pac_Apellidos;
    }

    public function setPacApellidos(string $Pac_Apellidos): static
    {
        $this->Pac_Apellidos = $Pac_Apellidos;

        return $this;
    }

    public function getPacFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->Pac_Fecha_Nacimiento;
    }

    public function setPacFechaNacimiento(?\DateTimeInterface $Pac_Fecha_Nacimiento): static
    {
        $this->Pac_Fecha_Nacimiento = $Pac_Fecha_Nacimiento;

        return $this;
    }

    public function getPacDireccionCompleta(): ?string
    {
        return $this->Pac_Direccion_Completa;
    }

    public function setPacDireccionCompleta(?string $Pac_Direccion_Completa): static
    {
        $this->Pac_Direccion_Completa = $Pac_Direccion_Completa;

        return $this;
    }

    public function getPacLenguaMaterna(): ?string
    {
        return $this->Pac_Lengua_Materna;
    }

    public function setPacLenguaMaterna(?string $Pac_Lengua_Materna): static
    {
        $this->Pac_Lengua_Materna = $Pac_Lengua_Materna;

        return $this;
    }

    public function getPacAntecedentes(): ?string
    {
        return $this->Pac_Antecedentes;
    }

    public function setPacAntecedentes(string $Pac_Antecedentes): static
    {
        $this->Pac_Antecedentes = $Pac_Antecedentes;

        return $this;
    }

    public function getPacAlergias(): ?string
    {
        return $this->Pac_Alergias;
    }

    public function setPacAlergias(string $Pac_Alergias): static
    {
        $this->Pac_Alergias = $Pac_Alergias;

        return $this;
    }

    public function getPacNombreCuidador(): ?string
    {
        return $this->Pac_Nombre_Cuidador;
    }

    public function setPacNombreCuidador(?string $Pac_Nombre_Cuidador): static
    {
        $this->Pac_Nombre_Cuidador = $Pac_Nombre_Cuidador;

        return $this;
    }

    public function getPacTelefonoCuidador(): ?string
    {
        return $this->Pac_Telefono_Cuidador;
    }

    public function setPacTelefonoCuidador(?string $Pac_Telefono_Cuidador): static
    {
        $this->Pac_Telefono_Cuidador = $Pac_Telefono_Cuidador;

        return $this;
    }

    public function getPacFechaIngreso(): ?\DateTimeInterface
    {
        return $this->Pac_Fecha_Ingreso;
    }

    public function setPacFechaIngreso(?\DateTimeInterface $Pac_Fecha_Ingreso): static
    {
        $this->Pac_Fecha_Ingreso = $Pac_Fecha_Ingreso;

        return $this;
    }

    public function getPacTimestamp(): ?\DateTimeInterface
    {
        return $this->Pac_Timestamp;
    }

    public function setPacTimestamp(?\DateTimeInterface $Pac_Timestamp): static
    {
        $this->Pac_Timestamp = $Pac_Timestamp;

        return $this;
    }
}
