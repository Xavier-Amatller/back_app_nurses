<?php

namespace App\Entity;

use App\Repository\DrenajeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrenajeRepository::class)]
#[ORM\Table(name: "drenajes")]
class Drenaje
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dre_debito = null;

    #[ORM\ManyToOne(inversedBy: 'drenajes')]
    #[ORM\JoinColumn(name: 'tdre_id', nullable: false)]
    private ?TiposDrenajes $tdre_id = null;

    #[ORM\OneToOne(mappedBy: 'dre_id', cascade: ['persist', 'remove'])]
    private ?Registro $registro = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDreDebito(): ?string
    {
        return $this->dre_debito;
    }

    public function setDreDebito(?string $dre_debito): static
    {
        $this->dre_debito = $dre_debito;

        return $this;
    }

    public function getTipoDrenaje(): ?TiposDrenajes
    {
        return $this->tdre_id;
    }

    public function setTipoDrenaje(?TiposDrenajes $tdre_id): static
    {
        $this->tdre_id = $tdre_id;

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
            $this->registro->setDrenaje(null);
        }

        // set the owning side of the relation if necessary
        if ($registro !== null && $registro->getDrenaje() !== $this) {
            $registro->setDrenaje($this);
        }

        $this->registro = $registro;

        return $this;
    }
}
