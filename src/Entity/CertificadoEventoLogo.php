<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CertificadoEventoLogo
 *
 * @ORM\Table(name="certificado_evento_logo", indexes={@ORM\Index(name="fk_certificado_evento_logo_certificado_evento1_idx", columns={"certificado_evento_id"}), @ORM\Index(name="fk_logo_logo1_idx", columns={"logo_id"})})
 * @ORM\Entity
 */
class CertificadoEventoLogo
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
     * @var \Logo
     *
     * @ORM\ManyToOne(targetEntity="Logo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="logo_id", referencedColumnName="id")
     * })
     */
    private $logo;
    
    /**
    * @var float
    *
    * @ORM\Column(name="x_eje", type="decimal", precision=9, scale=6, nullable=true, options={"default"=0})
    */
    private $x;
    
    /**
    * @var float
    *
    * @ORM\Column(name="y_eje", type="decimal", precision=9, scale=6, nullable=true, options={"default"=0})
    */
    private $y;
    
    /**
    * @var float
    *
    * @ORM\Column(name="ancho", type="decimal", precision=9, scale=6, nullable=true, options={"default"=0})
    */
    private $ancho;
    
    /**
    * @var float
    *
    * @ORM\Column(name="alto", type="decimal", precision=9, scale=6, nullable=true, options={"default"=0})
    */
    private $alto;
    
    public function __toString() {
        return ''.$this->getLogo();
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

    public function getLogo(): ?Logo
    {
        return $this->logo;
    }

    public function setLogo(?Logo $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getX(): ?string
    {
        return $this->x;
    }

    public function setX(?string $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?string
    {
        return $this->y;
    }

    public function setY(?string $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getAncho(): ?string
    {
        return $this->ancho;
    }

    public function setAncho(?string $ancho): self
    {
        $this->ancho = $ancho;

        return $this;
    }

    public function getAlto(): ?string
    {
        return $this->alto;
    }

    public function setAlto(?string $alto): self
    {
        $this->alto = $alto;

        return $this;
    }



}
