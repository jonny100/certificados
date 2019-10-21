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
    private $fechaObt = 'NULL';

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


}
