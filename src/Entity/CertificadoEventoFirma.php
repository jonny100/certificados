<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CertificadoEventoFirma
 *
 * @ORM\Table(name="certificado_evento_firma", indexes={@ORM\Index(name="fk_certificado_evento_firma_certificado_evento1_idx", columns={"certificado_evento_id"}), @ORM\Index(name="fk_firma_firma1_idx", columns={"firma_id"})})
 * @ORM\Entity
 */
class CertificadoEventoFirma
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
     * @var \CertificadoEvento
     *
     * @ORM\ManyToOne(targetEntity="CertificadoEvento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="certificado_evento_id", referencedColumnName="id")
     * })
     */
    private $certificadoEvento;

    /**
     * @var \Firma
     *
     * @ORM\ManyToOne(targetEntity="Firma")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="firma_id", referencedColumnName="id")
     * })
     */
    private $firma;
    
    public function __toString() {
        return ''.$this->getFirma();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFirma(): ?Firma
    {
        return $this->firma;
    }

    public function setFirma(?Firma $firma): self
    {
        $this->firma = $firma;

        return $this;
    }



}
