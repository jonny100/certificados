<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoEvento
 *
 * @ORM\Table(name="tipo_evento")
 * @ORM\Entity
 */
class TipoEvento
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


}
