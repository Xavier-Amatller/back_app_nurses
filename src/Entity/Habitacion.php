<?php

namespace App\Entity;

use App\Repository\HabitacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitacionRepository::class)]
#[ORM\Table(name: "habitaciones")]
class Habitacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $hab_id = null;

    #[ORM\Column(length: 255)]
    private ?string $hab_obs = null;

    #[ORM\OneToOne(targetEntity: Paciente::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'pac_numhistorial', referencedColumnName: 'pac_numhistorial', nullable: true)]
    private ?Paciente $pac_numhistorial = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHabId(): ?string
    {
        return $this->hab_id;
    }

    public function setHabId(string $hab_id): static
    {
        $this->hab_id = $hab_id;

        return $this;
    }

    public function getHabObs(): ?string
    {
        return $this->hab_obs;
    }

    public function setHabObs(string $hab_obs): static
    {
        $this->hab_obs = $hab_obs;

        return $this;
    }

    public function getPaciente(): ?Paciente
    {
        return $this->pac_numhistorial;
    }

    public function setPaciente(?Paciente $pac_numhistorial): static
    {
        $this->pac_numhistorial = $pac_numhistorial;

        return $this;
    }
}
