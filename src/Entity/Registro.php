<?php

namespace App\Entity;

use App\Repository\RegistroRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistroRepository::class)]
class Registro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'registros')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Auxiliar $aux_num_trabajador = null;

    #[ORM\ManyToOne(inversedBy: 'registros')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Paciente $pac_num_historial = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $reg_timestamp = null;

    #[ORM\OneToOne(inversedBy: 'registro', cascade: ['persist', 'remove'])]
    private ?ConstantesVitales $cv = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuxNumTrabajador(): ?Auxiliar
    {
        return $this->aux_num_trabajador;
    }

    public function setAuxNumTrabajador(?Auxiliar $aux_num_trabajador): static
    {
        $this->aux_num_trabajador = $aux_num_trabajador;

        return $this;
    }

    public function getPacNumHistorial(): ?Paciente
    {
        return $this->pac_num_historial;
    }

    public function setPacNumHistorial(?Paciente $pac_num_historial): static
    {
        $this->pac_num_historial = $pac_num_historial;

        return $this;
    }

    public function getRegTimestamp(): ?\DateTimeInterface
    {
        return $this->reg_timestamp;
    }

    public function setRegTimestamp(?\DateTimeInterface $reg_timestamp): static
    {
        $this->reg_timestamp = $reg_timestamp;

        return $this;
    }

    public function getCvId(): ?ConstantesVitales
    {
        return $this->cv;
    }

    public function setCvId(?ConstantesVitales $cv): static
    {
        $this->cv = $cv;

        return $this;
    }


}
