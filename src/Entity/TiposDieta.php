<?php

namespace App\Entity;

use App\Repository\TiposDietaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TiposDietaRepository::class)]
class TiposDieta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $TDie_Desc = null;

    /**
     * @var Collection<int, Dieta>
     */
    #[ORM\ManyToMany(targetEntity: Dieta::class, mappedBy: 'Tipos_Dietas')]
    private Collection $dietas;

    public function __construct()
    {
        $this->dietas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTDieDesc(): ?string
    {
        return $this->TDie_Desc;
    }

    public function setTDieDesc(string $TDie_Desc): static
    {
        $this->TDie_Desc = $TDie_Desc;

        return $this;
    }

    /**
     * @return Collection<int, Dieta>
     */
    public function getDietas(): Collection
    {
        return $this->dietas;
    }

    public function addDieta(Dieta $dieta): static
    {
        if (!$this->dietas->contains($dieta)) {
            $this->dietas->add($dieta);
            $dieta->addTiposDieta($this);
        }

        return $this;
    }

    public function removeDieta(Dieta $dieta): static
    {
        if ($this->dietas->removeElement($dieta)) {
            $dieta->removeTiposDieta($this);
        }

        return $this;
    }
}
