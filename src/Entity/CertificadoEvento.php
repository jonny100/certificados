<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CertificadoEvento
 *
 * @ORM\Table(name="certificado_evento", indexes={@ORM\Index(name="fk_certificado_evento_evento1_idx", columns={"evento_id"}), @ORM\Index(name="fk_certificado_evento_certificado1_idx", columns={"certificado_id"}), @ORM\Index(name="fk_certificado_evento_template1_idx", columns={"template_id"})})
 * @ORM\Entity
 */
class CertificadoEvento
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
     * @var \Certificado
     *
     * @ORM\ManyToOne(targetEntity="Certificado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="certificado_id", referencedColumnName="id")
     * })
     */
    private $certificado;

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
     * @var \Template
     *
     * @ORM\ManyToOne(targetEntity="Template")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="template_id", referencedColumnName="id")
     * })
     */
    private $template;
    
    /**
     * @ORM\OneToMany(targetEntity="CertificadoEventoRequisito", mappedBy="certificadoEvento", cascade={"all"}, orphanRemoval=true)
     */
    protected $requisitos;
    
    /**
     * @ORM\OneToMany(targetEntity="CertificadoEventoFirma", mappedBy="certificadoEvento", cascade={"all"}, orphanRemoval=true)
     */
    protected $firma;
    
    /**
     * @ORM\OneToMany(targetEntity="CertificadoEventoLogo", mappedBy="certificadoEvento", cascade={"all"}, orphanRemoval=true)
     */
    protected $logo;

    public function __construct()
    {
        $this->requisitos = new ArrayCollection();
        $this->firma = new ArrayCollection();
        $this->logo = new ArrayCollection();
    }
    
    public function __toString() {
        return ''.$this->getCertificado();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCertificado(): ?Certificado
    {
        return $this->certificado;
    }

    public function setCertificado(?Certificado $certificado): self
    {
        $this->certificado = $certificado;

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

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return Collection|CertificadoEventoRequisito[]
     */
    public function getRequisitos(): Collection
    {
        return $this->requisitos;
    }

    public function addRequisito(CertificadoEventoRequisito $requisito): self
    {
        if (!$this->requisitos->contains($requisito)) {
            $this->requisitos[] = $requisito;
            $requisito->setCertificadoEvento($this);
        }

        return $this;
    }

    public function removeRequisito(CertificadoEventoRequisito $requisito): self
    {
        if ($this->requisitos->contains($requisito)) {
            $this->requisitos->removeElement($requisito);
            // set the owning side to null (unless already changed)
            if ($requisito->getCertificadoEvento() === $this) {
                $requisito->setCertificadoEvento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CertificadoEventoFirma[]
     */
    public function getFirma(): Collection
    {
        return $this->firma;
    }

    public function addFirma(CertificadoEventoFirma $firma): self
    {
        if (!$this->firma->contains($firma)) {
            $this->firma[] = $firma;
            $firma->setCertificadoEvento($this);
        }

        return $this;
    }

    public function removeFirma(CertificadoEventoFirma $firma): self
    {
        if ($this->firma->contains($firma)) {
            $this->firma->removeElement($firma);
            // set the owning side to null (unless already changed)
            if ($firma->getCertificadoEvento() === $this) {
                $firma->setCertificadoEvento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CertificadoEventoLogo[]
     */
    public function getLogo(): Collection
    {
        return $this->logo;
    }

    public function addLogo(CertificadoEventoLogo $logo): self
    {
        if (!$this->logo->contains($logo)) {
            $this->logo[] = $logo;
            $logo->setCertificadoEvento($this);
        }

        return $this;
    }

    public function removeLogo(CertificadoEventoLogo $logo): self
    {
        if ($this->logo->contains($logo)) {
            $this->logo->removeElement($logo);
            // set the owning side to null (unless already changed)
            if ($logo->getCertificadoEvento() === $this) {
                $logo->setCertificadoEvento(null);
            }
        }

        return $this;
    }


}
