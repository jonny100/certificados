<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="evento", indexes={@ORM\Index(name="fk_evento_tipo_evento1_idx", columns={"tipo_evento_id"})})
 * @ORM\Entity
 */
class Evento
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
     * @ORM\Column(name="descripcion", type="string", length=450, nullable=true, options={"default"="NULL"})
     */
    private $descripcion = 'NULL';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_ini", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $fechaIni = 'NULL';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $fechaFin = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="cupo", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $cupo = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="evento_sgi_id", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $eventoSgiId = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';

    /**
     * @var \TipoEvento
     *
     * @ORM\ManyToOne(targetEntity="TipoEvento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_evento_id", referencedColumnName="id")
     * })
     */
    private $tipoEvento;

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

    public function getFechaIni(): ?\DateTimeInterface
    {
        return $this->fechaIni;
    }

    public function setFechaIni(?\DateTimeInterface $fechaIni): self
    {
        $this->fechaIni = $fechaIni;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTimeInterface $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getCupo(): ?int
    {
        return $this->cupo;
    }

    public function setCupo(?int $cupo): self
    {
        $this->cupo = $cupo;

        return $this;
    }

    public function getEventoSgiId(): ?int
    {
        return $this->eventoSgiId;
    }

    public function setEventoSgiId(?int $eventoSgiId): self
    {
        $this->eventoSgiId = $eventoSgiId;

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

    public function getTipoEvento(): ?TipoEvento
    {
        return $this->tipoEvento;
    }

    public function setTipoEvento(?TipoEvento $tipoEvento): self
    {
        $this->tipoEvento = $tipoEvento;

        return $this;
    }


}
