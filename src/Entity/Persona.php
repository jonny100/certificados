<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Persona
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity
 * @ApiResource
 */
class Persona
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="apellido_nombre", type="string", length=450, nullable=true, options={"default"="NULL"})
     */
    private $apellidoNombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dni", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $dni;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccion", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $direccion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true, options={"default"="NULL"})
     * @Assert\Email(message = "El email '{{ value }}' no es un email válido.")
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefono", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $telefono;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sexo", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $sexo;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_nac", type="date", nullable=true, options={"default"=NULL})
     */
    private $fechaNac;

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';
    
    public function __toString() {
        return ''.$this->getApellidoNombre();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApellidoNombre(): ?string
    {
        return $this->apellidoNombre;
    }

    public function setApellidoNombre(?string $apellidoNombre): self
    {
        $this->apellidoNombre = $apellidoNombre;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(?string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getFechaNac(): ?\DateTimeInterface
    {
        return $this->fechaNac;
    }

    public function setFechaNac(?\DateTimeInterface $fechaNac): self
    {
        $this->fechaNac = $fechaNac;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(?int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }


}
