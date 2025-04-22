<?php

namespace App\Entity;

use App\Repository\ConstantesVitalesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConstantesVitalesRepository::class)]
#[ORM\Table(name: "constantes_vitales")]
class ConstantesVitales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $cv_ta_sistolica = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $cv_ta_diastolica = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $cv_frequencia_respiratoria = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $cv_pulso = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $cv_temperatura = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0, nullable: true)]
    private ?string $cv_saturacion_oxigeno = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0, nullable: true)]
    private ?string $cv_talla = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0, nullable: true)]
    private ?string $cv_diuresis = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $cv_deposiciones = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0, nullable: true)]
    private ?string $cv_stp = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $cv_timestamp = null;

    #[ORM\OneToOne(mappedBy: 'cv_id', cascade: ['persist', 'remove'])]
    private ?Registro $registro = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCvTaSistolica(): ?string
    {
        return $this->cv_ta_sistolica;
    }

    public function setCvTaSistolica(string $cv_ta_sistolica): static
    {
        $this->cv_ta_sistolica = $cv_ta_sistolica;

        return $this;
    }

    public function getCvTaDiastolica(): ?string
    {
        return $this->cv_ta_diastolica;
    }

    public function setCvTaDiastolica(string $cv_ta_diastolica): static
    {
        $this->cv_ta_diastolica = $cv_ta_diastolica;

        return $this;
    }

    public function getCvFrequenciaRespiratoria(): ?string
    {
        return $this->cv_frequencia_respiratoria;
    }

    public function setCvFrequenciaRespiratoria(string $cv_frequencia_respiratoria): static
    {
        $this->cv_frequencia_respiratoria = $cv_frequencia_respiratoria;

        return $this;
    }

    public function getCvPulso(): ?string
    {
        return $this->cv_pulso;
    }

    public function setCvPulso(string $cv_pulso): static
    {
        $this->cv_pulso = $cv_pulso;

        return $this;
    }

    public function getCvTemperatura(): ?string
    {
        return $this->cv_temperatura;
    }

    public function setCvTemperatura(string $cv_temperatura): static
    {
        $this->cv_temperatura = $cv_temperatura;

        return $this;
    }

    public function getCvSaturacionOxigeno(): ?string
    {
        return $this->cv_saturacion_oxigeno;
    }

    public function setCvSaturacionOxigeno(?string $cv_saturacion_oxigeno): static
    {
        $this->cv_saturacion_oxigeno = $cv_saturacion_oxigeno;

        return $this;
    }

    public function getCvTalla(): ?string
    {
        return $this->cv_talla;
    }

    public function setCvTalla(?string $cv_talla): static
    {
        $this->cv_talla = $cv_talla;

        return $this;
    }

    public function getCvDiuresis(): ?string
    {
        return $this->cv_diuresis;
    }

    public function setCvDiuresis(?string $cv_diuresis): static
    {
        $this->cv_diuresis = $cv_diuresis;

        return $this;
    }

    public function getCvDeposiciones(): ?string
    {
        return $this->cv_deposiciones;
    }

    public function setCvDeposiciones(?string $cv_deposiciones): static
    {
        $this->cv_deposiciones = $cv_deposiciones;

        return $this;
    }

    public function getCvStp(): ?string
    {
        return $this->cv_stp;
    }

    public function setCvStp(?string $cv_stp): static
    {
        $this->cv_stp = $cv_stp;

        return $this;
    }

    public function getCvTimestamp(): ?\DateTimeInterface
    {
        return $this->cv_timestamp;
    }

    public function setCvTimestamp(\DateTimeInterface $cv_timestamp): static
    {
        $this->cv_timestamp = $cv_timestamp;

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
            $this->registro->setCosntantesVitales(null);
        }

        // set the owning side of the relation if necessary
        if ($registro !== null && $registro->getConstantesVitales() !== $this) {
            $registro->setCosntantesVitales($this);
        }

        $this->registro = $registro;

        return $this;
    }
}
