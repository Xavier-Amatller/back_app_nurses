<?php

namespace App\Entity;

use App\Repository\TiposDrenajesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TiposDrenajesRepository::class)]
class TiposDrenajes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tdre_desc = null;

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
}
