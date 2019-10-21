<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InscriptoEventoRequisito
 *
 * @ORM\Table(name="inscripto_evento_requisito", indexes={@ORM\Index(name="fk_inscripto_evento_requisito_certificado_evento_requisito1_idx", columns={"certificado_evento_requisito_id"}), @ORM\Index(name="fk_inscripto_evento_requisito_inscripto1_idx", columns={"inscripto_id"})})
 * @ORM\Entity
 */
class InscriptoEventoRequisito
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
     * @var bool|null
     *
     * @ORM\Column(name="excluir", type="boolean", nullable=true, options={"comment"="para casos en que un inscripto no necesite cumplir el requisito"})
     */
    private $excluir = '0';

    /**
     * @var \CertificadoEventoRequisito
     *
     * @ORM\ManyToOne(targetEntity="CertificadoEventoRequisito")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="certificado_evento_requisito_id", referencedColumnName="id")
     * })
     */
    private $certificadoEventoRequisito;

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
