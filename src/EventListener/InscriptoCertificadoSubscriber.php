<?php

namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\InscriptoCertificado;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InscriptoCertificadoSubscriber implements EventSubscriber {

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }


    public function getSubscribedEvents() {
        return array(
            'postPersist',
            'postUpdate'
        );
    }
    
    public function isEntitySupported($entity) {
        $supported = false;
        if ($entity instanceof InscriptoCertificado) {
            $supported = true;
        }
        return $supported;
    }

    public function postPersist(LifecycleEventArgs $args) {
        $this->index($args);
    }
    
    public function postUpdate(LifecycleEventArgs $args) {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args) {
	
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        if ($this->isEntitySupported($entity)) {
            //Genero o actualizo el codigo_verificacion
            /*
            * Código de verificación:
            * 2 dígitos para el año en curso (ej: 15 => 2015)
            * 2 dígitos para el mes en curso (ej: 03 => Marzo)
            * 2 dígitos para el día de hoy (ej: 25 => 25/03)
            * 2 dígitos para la hora en la que se genera el código (ej: 15 => 15:19)
            * 2 dígitos para los minutos de la hora en la que se genera el código (ej: 19 => 15:19)
            * 2 dígitos para los segundos de la hora en la que se genera el código (ej: 27 => 15:19:27)
            * 2 dígito random (ej: 45).
            */
            $codigo_verificacion = date('ymdHis').rand(10,99);
            
            $entity->setCodigoVerificacion($codigo_verificacion);
            $entityManager->persist($entity);
            $entityManager->flush();
        }
    }

    

}

?>
