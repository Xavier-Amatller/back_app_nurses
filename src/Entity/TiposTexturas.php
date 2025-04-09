<?php

namespace App\Entity;

use App\Repository\TiposTexturasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TiposTexturasRepository::class)]
class TiposTexturas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $TText_Desc = null;

    /**
     * @var Collection<int, Dieta>
     */
    #[ORM\OneToMany(targetEntity: Dieta::class, mappedBy: 'Die_TText')]
    private Collection $dietas;

    public function __construct()
    {
        $this->dietas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTTextDesc(): ?string
    {
        return $this->TText_Desc;
    }

    public function setTTextDesc(string $TText_Desc): static
    {
        $this->TText_Desc = $TText_Desc;

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
            $dieta->setDieTText($this);
        }

        return $this;
    }

    public function removeDieta(Dieta $dieta): static
    {
        if ($this->dietas->removeElement($dieta)) {
            // set the owning side to null (unless already changed)
            if ($dieta->getDieTText() === $this) {
                $dieta->setDieTText(null);
            }
        }

        return $this;
    }
}
