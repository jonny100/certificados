<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Firma
 *
 * @ORM\Table(name="firma")
 * @ORM\Entity
 */
class Firma
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
     * @ORM\Column(name="descripcion", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $descripcion;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="codigo", type="text", length=0, nullable=true, options={"default"="NULL"})
     */
    private $firma;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=450, nullable=true, options={"default"=NULL})
     */
    private $url;

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';
    
    public function __toString() {
        return ''.$this->getDescripcion();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFirma(): ?string
    {
        return $this->firma;
    }

    public function setFirma(?string $firma): self
    {
        $this->firma = $firma;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    

    


}
