<?php

namespace App\Entity;

use App\Repository\PacienteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PacienteRepository::class)]
#[ORM\Table(name: "pacientes")]
class Paciente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?int $pac_numhistorial = null;

    #[ORM\Column(length: 50)]
    private ?string $pac_nombre = null;

    #[ORM\Column(length: 150)]
    private ?string $pac_apellidos = null;

    #[ORM\Column(length: 255)]
    private ?string $pac_fecha_nacimiento = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $pac_fecha_nacimiento1 = null;

    #[ORM\Column(length: 255)]
    private ?string $pac_direccion_completa = null;

    #[ORM\Column(length: 45)]
    private ?string $pac_lengua_materna = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $pac_antecedentes = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $pac_alergias = null;

    #[ORM\Column(length: 150)]
    private ?string $pac_nombre_cuidador = null;

    #[ORM\Column(length: 9)]
    private ?string $pac_telefono_cuidador = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $pac_fecha_ingreso = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $pac_timestamp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPacNumhistorial(): ?int
    {
        return $this->pac_numhistorial;
    }

    public function setPacNumhistorial(int $pac_numhistorial): static
    {
        $this->pac_numhistorial = $pac_numhistorial;

        return $this;
    }

    public function getPacNombre(): ?string
    {
        return $this->pac_nombre;
    }

    public function setPacNombre(string $pac_nombre): static
    {
        $this->pac_nombre = $pac_nombre;

        return $this;
    }

    public function getPacApellidos(): ?string
    {
        return $this->pac_apellidos;
    }

    public function setPacApellidos(string $pac_apellidos): static
    {
        $this->pac_apellidos = $pac_apellidos;

        return $this;
    }

    public function getPacFechaNacimiento(): ?string
    {
        return $this->pac_fecha_nacimiento;
    }

    public function setPacFechaNacimiento(string $pac_fecha_nacimiento): static
    {
        $this->pac_fecha_nacimiento = $pac_fecha_nacimiento;

        return $this;
    }

    public function getPacFechaNacimiento1(): ?\DateTimeInterface
    {
        return $this->pac_fecha_nacimiento1;
    }

    public function setPacFechaNacimiento1(\DateTimeInterface $pac_fecha_nacimiento1): static
    {
        $this->pac_fecha_nacimiento1 = $pac_fecha_nacimiento1;

        return $this;
    }

    public function getPacDireccionCompleta(): ?string
    {
        return $this->pac_direccion_completa;
    }

    public function setPacDireccionCompleta(string $pac_direccion_completa): static
    {
        $this->pac_direccion_completa = $pac_direccion_completa;

        return $this;
    }

    public function getPacLenguaMaterna(): ?string
    {
        return $this->pac_lengua_materna;
    }

    public function setPacLenguaMaterna(string $pac_lengua_materna): static
    {
        $this->pac_lengua_materna = $pac_lengua_materna;

        return $this;
    }

    public function getPacAntecedentes(): ?string
    {
        return $this->pac_antecedentes;
    }

    public function setPacAntecedentes(string $pac_antecedentes): static
    {
        $this->pac_antecedentes = $pac_antecedentes;

        return $this;
    }

    public function getPacAlergias(): ?string
    {
        return $this->pac_alergias;
    }

    public function setPacAlergias(string $pac_alergias): static
    {
        $this->pac_alergias = $pac_alergias;

        return $this;
    }

    public function getPacNombreCuidador(): ?string
    {
        return $this->pac_nombre_cuidador;
    }

    public function setPacNombreCuidador(string $pac_nombre_cuidador): static
    {
        $this->pac_nombre_cuidador = $pac_nombre_cuidador;

        return $this;
    }

    public function getPacTelefonoCuidador(): ?string
    {
        return $this->pac_telefono_cuidador;
    }

    public function setPacTelefonoCuidador(string $pac_telefono_cuidador): static
    {
        $this->pac_telefono_cuidador = $pac_telefono_cuidador;

        return $this;
    }

    public function getPacFechaIngreso(): ?\DateTimeInterface
    {
        return $this->pac_fecha_ingreso;
    }

    public function setPacFechaIngreso(\DateTimeInterface $pac_fecha_ingreso): static
    {
        $this->pac_fecha_ingreso = $pac_fecha_ingreso;

        return $this;
    }

    public function getPacTimestamp(): ?\DateTimeInterface
    {
        return $this->pac_timestamp;
    }

    public function setPacTimestamp(\DateTimeInterface $pac_timestamp): static
    {
        $this->pac_timestamp = $pac_timestamp;

        return $this;
    }
}
