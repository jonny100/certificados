<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * InscriptoCertificado
 *
 * @ORM\Table(name="inscripto_certificado", indexes={@ORM\Index(name="fk_inscripto_certificado_certificado_evento1_idx", columns={"certificado_evento_id"}), @ORM\Index(name="fk_inscripto_certificado_inscripto1_idx", columns={"inscripto_id"}), @ORM\Index(name="fk_inscripto_certificado_codigoverificacion1_idx", columns={"codigo_verificacion"})})
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
     * @ORM\Column(name="fecha_obt", type="datetime", nullable=true, options={"default"=NULL})
     */
    private $fechaObt;

    /**
     * @var \EstadoInscriptoCertificado
     *
     * @ORM\ManyToOne(targetEntity="EstadoInscriptoCertificado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado_inscripto_certificado_id", referencedColumnName="id")
     * })
     */
    private $estado;

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
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="codigo_verificacion", type="string", length=450, nullable=true, options={"default"="NULL"})
     */
    private $codigoVerificacion;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="texto_certificado", type="text", length=0, nullable=true, options={"default"=NULL})
     */
    private $textoCertificado;
    
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_autorizacion", type="datetime", nullable=true, options={"default"=NULL})
     */
    private $fechaAutorizacion;
    
    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_autorizador", referencedColumnName="id")
     * })
     */
    private $userAutorizador;
    
    
    
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

    public function getCodigoVerificacion(): ?string
    {
        return $this->codigoVerificacion;
    }

    public function setCodigoVerificacion(?string $codigoVerificacion): self
    {
        $this->codigoVerificacion = $codigoVerificacion;

        return $this;
    }

    public function getTextoCertificado(): ?string
    {
        return $this->textoCertificado;
    }

    public function setTextoCertificado(?string $textoCertificado): self
    {
        $this->textoCertificado = $textoCertificado;

        return $this;
    }

    public function getEstado(): ?EstadoInscriptoCertificado
    {
        return $this->estado;
    }

    public function setEstado(?EstadoInscriptoCertificado $estado): self
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

    public function getFechaAutorizacion(): ?\DateTimeInterface
    {
        return $this->fechaAutorizacion;
    }

    public function setFechaAutorizacion(?\DateTimeInterface $fechaAutorizacion): self
    {
        $this->fechaAutorizacion = $fechaAutorizacion;

        return $this;
    }

    public function getUserAutorizador(): ?User
    {
        return $this->userAutorizador;
    }

    public function setUserAutorizador(?User $userAutorizador): self
    {
        $this->userAutorizador = $userAutorizador;

        return $this;
    }

    


}
