<?php

namespace App\Entity;

use App\Repository\AuxiliaresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuxiliaresRepository::class)]
class Auxiliares
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $Aux_NumTrabajador = null;

    #[ORM\Column(length: 50)]
    private ?string $Aux_Nombre = null;

    #[ORM\Column(length: 150)]
    private ?string $Aux_Apellidos = null;

    #[ORM\Column(length: 255)]
    private ?string $Aux_Password = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuxNumTrabajador(): ?string
    {
        return $this->Aux_NumTrabajador;
    }

    public function setAuxNumTrabajador(string $Aux_NumTrabajador): static
    {
        $this->Aux_NumTrabajador = $Aux_NumTrabajador;

        return $this;
    }

    public function getAuxNombre(): ?string
    {
        return $this->Aux_Nombre;
    }

    public function setAuxNombre(string $Aux_Nombre): static
    {
        $this->Aux_Nombre = $Aux_Nombre;

        return $this;
    }

    public function getAuxApellidos(): ?string
    {
        return $this->Aux_Apellidos;
    }

    public function setAuxApellidos(string $Aux_Apellidos): static
    {
        $this->Aux_Apellidos = $Aux_Apellidos;

        return $this;
    }

    public function getAuxPassword(): ?string
    {
        return $this->Aux_Password;
    }

    public function setAuxPassword(string $Aux_Password): static
    {
        $this->Aux_Password = $Aux_Password;

        return $this;
    }

}
