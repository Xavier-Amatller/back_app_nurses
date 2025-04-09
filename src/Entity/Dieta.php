<?php

namespace App\Entity;

use App\Repository\DietaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DietaRepository::class)]
#[ORM\Table(name: "dietas")]
class Dieta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'die_id', cascade: ['persist', 'remove'])]
    private ?Registro $registro = null;

    #[ORM\ManyToOne(inversedBy: 'dietas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TiposTexturas $Die_TText = null;

    /**
     * @var Collection<int, TiposDieta>
     */
    #[ORM\ManyToMany(targetEntity: TiposDieta::class, inversedBy: 'dietas')]
    private Collection $Tipos_Dietas;

    #[ORM\Column]
    private ?bool $Die_Autonomo = null;

    #[ORM\Column]
    private ?bool $Die_Protesi = null;

    public function __construct()
    {
        $this->Tipos_Dietas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistro(): ?Registro
    {
        return $this->registro;
    }

    public function setRegistro(?Registro $registro): static
    {
        // unset the owning side of the relation if necessary
        if ($registro === null && $this->registro !== null) {
            $this->registro->setDieId(null);
        }

        // set the owning side of the relation if necessary
        if ($registro !== null && $registro->getDieId() !== $this) {
            $registro->setDieId($this);
        }

        $this->registro = $registro;

        return $this;
    }

    public function getDieTText(): ?TiposTexturas
    {
        return $this->Die_TText;
    }

    public function setDieTText(?TiposTexturas $Die_TText): static
    {
        $this->Die_TText = $Die_TText;

        return $this;
    }

    /**
     * @return Collection<int, TiposDieta>
     */
    public function getTiposDietas(): Collection
    {
        return $this->Tipos_Dietas;
    }

    public function addTiposDieta(TiposDieta $tiposDieta): static
    {
        if (!$this->Tipos_Dietas->contains($tiposDieta)) {
            $this->Tipos_Dietas->add($tiposDieta);
        }

        return $this;
    }

    public function removeTiposDieta(TiposDieta $tiposDieta): static
    {
        $this->Tipos_Dietas->removeElement($tiposDieta);

        return $this;
    }

    public function isDieAutonomo(): ?bool
    {
        return $this->Die_Autonomo;
    }

    public function setDieAutonomo(bool $Die_Autonomo): static
    {
        $this->Die_Autonomo = $Die_Autonomo;

        return $this;
    }

    public function isDieProtesi(): ?bool
    {
        return $this->Die_Protesi;
    }

    public function setDieProtesi(bool $Die_Protesi): static
    {
        $this->Die_Protesi = $Die_Protesi;

        return $this;
    }
}
