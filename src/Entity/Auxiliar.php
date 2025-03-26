<?php

namespace App\Entity;

use App\Repository\AuxiliarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuxiliarRepository::class)]
#[ORM\Table(name: "auxiliares")]
class Auxiliar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   #[ORM\Column(name: "aux_num_trabajador", length: 10, unique: true)]
    private ?string $aux_num_trabajador = null;

    #[ORM\Column(name: "aux_nombre", length: 50)]
    private ?string $aux_nombre = null;

    #[ORM\Column(name: "aux_apellidos", length: 150)]
    private ?string $aux_apellidos = null;

    #[ORM\Column(name: "aux_password", length: 255)]
    private ?string $aux_password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuxNumTrabajador(): ?string
    {
        return $this->aux_num_trabajador;
    }

    public function setAuxNumTrabajador(string $aux_num_trabajador): static
    {
        $this->aux_num_trabajador = $aux_num_trabajador;

        return $this;
    }

    public function getAuxNombre(): ?string
    {
        return $this->aux_nombre;
    }

    public function setAuxNombre(string $aux_nombre): static
    {
        $this->aux_nombre = $aux_nombre;

        return $this;
    }

    public function getAuxApellidos(): ?string
    {
        return $this->aux_apellidos;
    }

    public function setAuxApellidos(string $aux_apellidos): static
    {
        $this->aux_apellidos = $aux_apellidos;

        return $this;
    }

    public function getAuxPassword(): ?string
    {
        return $this->aux_password;
    }

    public function setAuxPassword(string $aux_password): static
    {
        $this->aux_password = $aux_password;

        return $this;
    }
}
