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

    #[ORM\OneToOne(inversedBy: 'registro', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'die_id', referencedColumnName: 'id', nullable: true)]
    private ?Dieta $die_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuxId(): ?Auxiliar
    {
        return $this->aux_id;
    }

    public function setAuxId(?Auxiliar $aux_id): static
    {
        $this->aux_id = $aux_id;

        return $this;
    }

    public function getPacId(): ?Paciente
    {
        return $this->pac_id;
    }

    public function setPacId(?Paciente $pac_id): static
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

    public function getCvId(): ?ConstantesVitales
    {
        return $this->cv_id;
    }

    public function setCvId(?ConstantesVitales $cv_id): static
    {
        $this->cv_id = $cv_id;

        return $this;
    }

    public function getDieId(): ?Dieta
    {
        return $this->die_id;
    }

    public function setDieId(?Dieta $die): static
    {
        $this->die_id = $die;

        return $this;
    }


}
