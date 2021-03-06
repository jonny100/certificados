<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CertificadoEventoRequisito
 *
 * @ORM\Table(name="certificado_evento_requisito", indexes={@ORM\Index(name="fk_certificado_evento_requisito_certificado_evento1_idx", columns={"certificado_evento_id"}), @ORM\Index(name="fk_evento_requisito_requisito1_idx", columns={"requisito_id"})})
 * @ORM\Entity
 */
class CertificadoEventoRequisito
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
     * @var \Requisito
     *
     * @ORM\ManyToOne(targetEntity="Requisito")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requisito_id", referencedColumnName="id")
     * })
     */
    private $requisito;
    
    public function __toString() {
        return ''.$this->getRequisito();
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

    public function getRequisito(): ?Requisito
    {
        return $this->requisito;
    }

    public function setRequisito(?Requisito $requisito): self
    {
        $this->requisito = $requisito;

        return $this;
    }


}
