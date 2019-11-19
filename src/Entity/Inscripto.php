<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Inscripto
 *
 * @ORM\Table(name="inscripto", indexes={@ORM\Index(name="fk_inscripto_persona1_idx", columns={"persona_id"}), @ORM\Index(name="fk_inscripto_evento1_idx", columns={"evento_id"})})
 * @ORM\Entity
 */
class Inscripto
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_insc", type="datetime", nullable=true, options={"default"=NULL})
     */
    private $fechaInsc;

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';

    /**
     * @var \Evento
     *
     * @ORM\ManyToOne(targetEntity="Evento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evento_id", referencedColumnName="id")
     * })
     */
    private $evento;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     * })
     */
    private $persona;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="legajo", type="string", length=450, nullable=true, options={"default"="NULL"})
     */
    private $legajo;
    
    /**
     * @ORM\OneToMany(targetEntity="InscriptoCertificado", mappedBy="inscripto", cascade={"all"}, orphanRemoval=true)
     */
    protected $certificados;
    
    /**
     * @ORM\OneToMany(targetEntity="InscriptoEventoRequisito", mappedBy="inscripto", cascade={"all"}, orphanRemoval=true)
     */
    protected $requisitos;

    public function __construct()
    {
        $this->certificados = new ArrayCollection();
        $this->requisitos = new ArrayCollection();
    }
    
    public function __toString() {
        return ''.$this->getPersona();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaInsc(): ?\DateTimeInterface
    {
        return $this->fechaInsc;
    }

    public function setFechaInsc(?\DateTimeInterface $fechaInsc): self
    {
        $this->fechaInsc = $fechaInsc;

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

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(?Evento $evento): self
    {
        $this->evento = $evento;

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * @return Collection|InscriptoCertificado[]
     */
    public function getCertificados(): Collection
    {
        return $this->certificados;
    }

    public function addCertificado(InscriptoCertificado $certificado): self
    {
        if (!$this->certificados->contains($certificado)) {
            $this->certificados[] = $certificado;
            $certificado->setInscripto($this);
        }

        return $this;
    }

    public function removeCertificado(InscriptoCertificado $certificado): self
    {
        if ($this->certificados->contains($certificado)) {
            $this->certificados->removeElement($certificado);
            // set the owning side to null (unless already changed)
            if ($certificado->getInscripto() === $this) {
                $certificado->setInscripto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InscriptoEventoRequisito[]
     */
    public function getRequisitos(): Collection
    {
        return $this->requisitos;
    }

    public function addRequisito(InscriptoEventoRequisito $requisito): self
    {
        if (!$this->requisitos->contains($requisito)) {
            $this->requisitos[] = $requisito;
            $requisito->setInscripto($this);
        }

        return $this;
    }

    public function removeRequisito(InscriptoEventoRequisito $requisito): self
    {
        if ($this->requisitos->contains($requisito)) {
            $this->requisitos->removeElement($requisito);
            // set the owning side to null (unless already changed)
            if ($requisito->getInscripto() === $this) {
                $requisito->setInscripto(null);
            }
        }

        return $this;
    }

    public function getLegajo(): ?string
    {
        return $this->legajo;
    }

    public function setLegajo(?string $legajo): self
    {
        $this->legajo = $legajo;

        return $this;
    }


}
