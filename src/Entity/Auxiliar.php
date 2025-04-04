<?php

namespace App\Entity;

use App\Repository\AuxiliarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AuxiliarRepository::class)]
#0 
class Auxiliar implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "aux_num_trabajador", length: 10, unique: true)]
    private ?string $aux_num_trabajador = null;

    #[ORM\Column(name: "aux_nombre", length: 50)]
    private ?string $aux_nombre = null;

    #[ORM\Column(name: "aux_apellidos", length: 150)]
    private ?string $aux_apellidos = null;

    #[ORM\Column(name: "aux_password", length: 255)]
    private ?string $aux_password = null;

    /**
     * @var Collection<int, Registro>
     */
    #[ORM\OneToMany(targetEntity: Registro::class, mappedBy: 'aux_num_trabajador')]
    private Collection $registros;

    public function __construct()
    {
        $this->registros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuxNumTrabajador(): ?string
    {
        return $this->aux_num_trabajador;
    }

    public function setAuxNumTrabajador(string $aux_num_trabajador): static
    {
        $this->aux_num_trabajador = $aux_num_trabajador;

        return $this;
    }

    public function getAuxNombre(): ?string
    {
        return $this->aux_nombre;
    }

    public function setAuxNombre(string $aux_nombre): static
    {
        $this->aux_nombre = $aux_nombre;

        return $this;
    }

    public function getAuxApellidos(): ?string
    {
        return $this->aux_apellidos;
    }

    public function setAuxApellidos(string $aux_apellidos): static
    {
        $this->aux_apellidos = $aux_apellidos;

        return $this;
    }

    public function getAuxPassword(): ?string
    {
        return $this->aux_password;
    }

    public function setAuxPassword(string $aux_password): static
    {
        $this->aux_password = $aux_password;

        return $this;
    }


    // Métodos requeridos por UserInterface
    public function getRoles(): array
    {
        return ['ROLE_AUXILIAR'];
    }

    public function getPassword(): ?string
    {
        return $this->aux_password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        // Mantén esto por compatibilidad, pero no es obligatorio si usas getUserIdentifier
        return $this->aux_num_trabajador ?? '';
    }

    public function eraseCredentials(): void
    {
        // No hay nada que borrar en este caso
    }

    // Método obligatorio desde Symfony 5.3+
    public function getUserIdentifier(): string
    {
        // Usamos aux_num_trabajador como identificador único
        return $this->aux_num_trabajador ?? '';
    }

    /**
     * @return Collection<int, Registro>
     */
    public function getRegistros(): Collection
    {
        return $this->registros;
    }

    public function addRegistros(Registro $registro): static
    {
        if (!$this->registros->contains($registro)) {
            $this->registros->add($registro);
            $registro->setAuxNumTrabajador($this);
        }

        return $this;
    }

    public function removeRegistro(Registro $registro): static
    {
        if ($this->registros->removeElement($registro)) {
            // set the owning side to null (unless already changed)
            if ($registro->getAuxNumTrabajador() === $this) {
                $registro->setAuxNumTrabajador(null);
            }
        }

        return $this;
    }
}
