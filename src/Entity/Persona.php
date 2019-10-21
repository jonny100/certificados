<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persona
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity
 */
class Persona
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
     * @ORM\Column(name="apellido_nombre", type="string", length=450, nullable=true, options={"default"="NULL"})
     */
    private $apellidoNombre = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="dni", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $dni = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccion", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $direccion = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $email = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefono", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $telefono = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="sexo", type="string", length=45, nullable=true, options={"default"="NULL"})
     */
    private $sexo = 'NULL';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_nac", type="date", nullable=true, options={"default"="NULL"})
     */
    private $fechaNac = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';


}
