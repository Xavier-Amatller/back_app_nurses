<?php

namespace App\Entity;

use App\Repository\TiposDrenajesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: TiposDrenajesRepository::class)]
class TiposDrenajes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tdre_desc = null;

    /**
     * @var Collection<int, Drenaje>
     */
    #[ORM\OneToMany(targetEntity: Drenaje::class, mappedBy: 'tdre_id')]
    private Collection $drenajes;


    public function __construct()
    {
        $this->drenajes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTDreDesc(): ?string
    {
        return $this->tdre_desc;
    }

    public function setTDreDesc(?string $tdre_desc): static
    {
        $this->tdre_desc = $tdre_desc;

        return $this;
    }

    
    /**
     * @return Collection<int, Drenaje>
     */
    public function getDrenajes(): Collection
    {
        return $this->drenajes;
    }

    public function addDrenaje(Drenaje $drenaje): static
    {
        if (!$this->drenajes->contains($drenaje)) {
            $this->drenajes->add($drenaje);
            $drenaje->setTipoDrenaje($this);
        }

        return $this;
    }

    public function removeDrenaje(Drenaje $drenaje): static
    {
        if ($this->drenajes->removeElement($drenaje)) {
            // set the owning side to null (unless already changed)
            if ($drenaje->getTipoDrenaje() === $this) {
                $drenaje->setTipoDrenaje(null);
            }
        }

        return $this;
    }
}
