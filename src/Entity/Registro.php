<?php

namespace App\Entity;

use App\Repository\RegistroRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistroRepository::class)]
#[ORM\Table(name: "registros")]
class Registro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'registros')]
    #[ORM\JoinColumn(name: 'aux_id', nullable: false)]
    private ?Auxiliar $aux_id = null;

    #[ORM\ManyToOne(inversedBy: 'registros')]
    #[ORM\JoinColumn(name: 'pac_id', nullable: false)]
    private ?Paciente $pac_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $reg_timestamp = null;

    #[ORM\OneToOne(targetEntity: ConstantesVitales::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'cv_id', referencedColumnName: 'id', nullable: true)]
    private ?ConstantesVitales $cv_id = null;

    #[ORM\OneToOne(targetEntity: Dieta::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'die_id', referencedColumnName: 'id', nullable: true)]
    private ?Dieta $die_id = null;

    #[ORM\OneToOne(targetEntity: Movilizacion::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'mov_id', referencedColumnName: 'id', nullable: true)]
    private ?Movilizacion $mov_id = null;

    #[ORM\OneToOne(targetEntity: Diagnostico::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'dia_id', referencedColumnName: 'id', nullable: true)]
    private ?Diagnostico $dia_id = null;

    #[ORM\OneToOne(targetEntity: Drenaje::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'dre_id', referencedColumnName: 'id', nullable: true)]
    private ?Drenaje $dre_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuxiliar(): ?Auxiliar
    {
        return $this->aux_id;
    }

    public function setAuxiliar(?Auxiliar $aux_id): static
    {
        $this->aux_id = $aux_id;

        return $this;
    }

    public function getPaciente(): ?Paciente
    {
        return $this->pac_id;
    }

    public function setPaciente(?Paciente $pac_id): static
    {
        $this->pac_id = $pac_id;

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

    public function getConstantesVitales(): ?ConstantesVitales
    {
        return $this->cv_id;
    }

    public function setConstantesVitales(?ConstantesVitales $cv_id): static
    {
        $this->cv_id = $cv_id;

        return $this;
    }

    public function getDieta(): ?Dieta
    {
        return $this->die_id;
    }

    public function setDieta(?Dieta $die_id): static
    {
        $this->die_id = $die_id;

        return $this;
    }

    public function getMovilizacion(): ?Movilizacion
    {
        return $this->mov_id;
    }

    public function setMovilizacion(?Movilizacion $mov_id): static
    {
        $this->mov_id = $mov_id;

        return $this;
    }

    public function getDiagnostico(): ?Diagnostico
    {
        return $this->dia_id;
    }

    public function setDiagnostico(?Diagnostico $dia_id): static
    {
        $this->dia_id = $dia_id;

        return $this;
    }

    public function getDrenaje(): ?Drenaje
    {
        return $this->dre_id;
    }

    public function setDrenaje(?Drenaje $dre_id): static
    {
        $this->dre_id = $dre_id;

        return $this;
    }
}
