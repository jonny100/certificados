<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\EventoApiController;

/**
 * Evento
 *
 * @ORM\Table(name="evento", indexes={@ORM\Index(name="fk_evento_tipo_evento1_idx", columns={"tipo_evento_id"})})
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={
            "get"={"method"="GET"},
            "pre_evento"={
               "path"="/eventos/preevento",
               "method"="POST",
               "controller"=EventoApiController::class     
             }
   }
 * )
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
    private $descripcion;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_ini", type="datetime", nullable=true, options={"default"=NULL})
     */
    private $fechaIni;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true, options={"default"=NULL})
     */
    private $fechaFin;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cupo", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $cupo;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="correspondiente", type="string", length=450, nullable=true, options={"default"="NULL"})
     */
    private $correspondiente;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="resolucion", type="string", length=450, nullable=true, options={"default"="NULL"})
     */
    private $resolucion;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="horas", type="string", length=450, nullable=true, options={"default"="NULL"})
     */
    private $horas;

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
     * @ORM\ManyToOne(targetEntity="TipoEvento", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_evento_id", referencedColumnName="id")
     * })
     */
    private $tipoEvento;
    
    /**
     * @ORM\OneToMany(targetEntity="CertificadoEvento", mappedBy="evento", cascade={"all"}, orphanRemoval=true)
     */
    protected $certificados;
    
    /**
     * @ORM\OneToMany(targetEntity="Inscripto", mappedBy="evento", cascade={"all"}, orphanRemoval=true)
     */
    protected $inscriptos;

    public function __construct()
    {
        $this->certificados = new ArrayCollection();
        $this->inscriptos = new ArrayCollection();
    }
    
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

    /**
     * @return Collection|CertificadoEvento[]
     */
    public function getCertificados(): Collection
    {
        return $this->certificados;
    }
    
    public function setCertificados($certificados) {
        $this->certificados = $certificados;
        foreach ($certificados as $certificado) {
            $certificado->setEvento($this);
        }
    }

    public function addCertificado(CertificadoEvento $certificado): self
    {
        if (!$this->certificados->contains($certificado)) {
            $this->certificados[] = $certificado;
            $certificado->setEvento($this);
        }

        return $this;
    }

    public function removeCertificado(CertificadoEvento $certificado): self
    {
        if ($this->certificados->contains($certificado)) {
            $this->certificados->removeElement($certificado);
            // set the owning side to null (unless already changed)
            if ($certificado->getEvento() === $this) {
                $certificado->setEvento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Inscripto[]
     */
    public function getInscriptos(): Collection
    {
        return $this->inscriptos;
    }

    public function addInscripto(Inscripto $inscripto): self
    {
        if (!$this->inscriptos->contains($inscripto)) {
            $this->inscriptos[] = $inscripto;
            $inscripto->setEvento($this);
        }

        return $this;
    }

    public function removeInscripto(Inscripto $inscripto): self
    {
        if ($this->inscriptos->contains($inscripto)) {
            $this->inscriptos->removeElement($inscripto);
            // set the owning side to null (unless already changed)
            if ($inscripto->getEvento() === $this) {
                $inscripto->setEvento(null);
            }
        }

        return $this;
    }

    public function getCorrespondiente(): ?string
    {
        return $this->correspondiente;
    }

    public function setCorrespondiente(?string $correspondiente): self
    {
        $this->correspondiente = $correspondiente;

        return $this;
    }

    public function getResolucion(): ?string
    {
        return $this->resolucion;
    }

    public function setResolucion(?string $resolucion): self
    {
        $this->resolucion = $resolucion;

        return $this;
    }

    public function getHoras(): ?string
    {
        return $this->horas;
    }

    public function setHoras(?string $horas): self
    {
        $this->horas = $horas;

        return $this;
    }
    
    


}
