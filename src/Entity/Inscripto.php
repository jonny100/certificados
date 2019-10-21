<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscripto
 *
 * @ORM\Table(name="inscripto", indexes={@ORM\Index(name="fk_inscripto_persona1_idx", columns={"persona_id"}), @ORM\Index(name="fk_inscripto_evento1_idx", columns={"evento_id"})})
 * @ORM\Entity
 */
class Inscripto
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
     * @ORM\Column(name="fecha_insc", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $fechaInsc = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';

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
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     * })
     */
    private $persona;


}
