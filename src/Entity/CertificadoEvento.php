<?php

namespace App\Entity;

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


}
