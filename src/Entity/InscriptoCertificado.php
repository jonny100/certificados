<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InscriptoCertificado
 *
 * @ORM\Table(name="inscripto_certificado", indexes={@ORM\Index(name="fk_inscripto_certificado_certificado_evento1_idx", columns={"certificado_evento_id"}), @ORM\Index(name="fk_inscripto_certificado_inscripto1_idx", columns={"inscripto_id"})})
 * @ORM\Entity
 */
class InscriptoCertificado
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
     * @ORM\Column(name="fecha_obt", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $fechaObt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';

    /**
     * @var \CertificadoEvento
     *
     * @ORM\ManyToOne(targetEntity="CertificadoEvento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="certificado_evento_id", referencedColumnName="id")
     * })
     */
    private $certificadoEvento;

    /**
     * @var \Inscripto
     *
     * @ORM\ManyToOne(targetEntity="Inscripto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inscripto_id", referencedColumnName="id")
     * })
     */
    private $inscripto;
    
    public function __toString() {
        return ''.$this->getCertificadoEvento();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaObt(): ?\DateTimeInterface
    {
        return $this->fechaObt;
    }

    public function setFechaObt(?\DateTimeInterface $fechaObt): self
    {
        $this->fechaObt = $fechaObt;

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

    public function getCertificadoEvento(): ?CertificadoEvento
    {
        return $this->certificadoEvento;
    }

    public function setCertificadoEvento(?CertificadoEvento $certificadoEvento): self
    {
        $this->certificadoEvento = $certificadoEvento;

        return $this;
    }

    public function getInscripto(): ?Inscripto
    {
        return $this->inscripto;
    }

    public function setInscripto(?Inscripto $inscripto): self
    {
        $this->inscripto = $inscripto;

        return $this;
    }


}
