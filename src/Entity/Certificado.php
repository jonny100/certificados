<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Certificado
 *
 * @ORM\Table(name="certificado", indexes={@ORM\Index(name="fk_certificados_tipo_certificado_idx", columns={"tipo_certificado_id"})})
 * @ORM\Entity
 */
class Certificado
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
     * @ORM\Column(name="descripcion", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $descripcion = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';

    /**
     * @var \TipoCertificado
     *
     * @ORM\ManyToOne(targetEntity="TipoCertificado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_certificado_id", referencedColumnName="id")
     * })
     */
    private $tipoCertificado;


}
