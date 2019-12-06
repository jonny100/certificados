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
            
            $texto = $this->reemplazarVariables($entity);
            $entity->setTextoCertificado($texto);
            
            $entityManager->persist($entity);
            $entityManager->flush();
        }
    }
    
    public function reemplazarVariables($obj){
        $el_la = 'el Sr.';
        if($obj->getInscripto()->getPersona()->getSexo() == 'F') $el_la = 'la Sra.';
        
        $apellydoynombre = $obj->getInscripto()->getPersona()->getApellidoNombre();
        $dni = $obj->getInscripto()->getPersona()->getDNI();
        $legajo = $obj->getInscripto()->getLegajo();
        $cursonombre = $obj->getCertificadoEvento()->getEvento()->getDescripcion();
        $correspondiente = $obj->getCertificadoEvento()->getEvento()->getCorrespondiente();
        $resolucion = $obj->getCertificadoEvento()->getEvento()->getResolucion();
        $horas = $obj->getCertificadoEvento()->getEvento()->getHoras();
        
        $fechaObt = $obj->getFechaObt();
        $dia = $fechaObt->format('d');
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        $mes = ucwords(iconv('ISO-8859-2', 'UTF-8', strftime("%B", $fechaObt->getTimestamp())));
        $anio = $fechaObt->format('Y');
        
        $template = null !== $obj->getCertificadoEvento()->getTemplate() ? $obj->getCertificadoEvento()->getTemplate()->getCodigo() : '';  
        $template = str_replace('#el-la#', $el_la, $template);
        $template = str_replace('#apellidoynombre#', $apellydoynombre, $template);
        $template = str_replace('#dni#', $dni, $template);
        $template = str_replace('#legajo#', $legajo, $template);
        $template = str_replace('#cursonombre#', $cursonombre, $template);
        $template = str_replace('#correspondiente#', $correspondiente, $template);
        $template = str_replace('#resolucion#', $resolucion, $template);
        $template = str_replace('#horas#', $horas, $template);
        
        $template = str_replace('#dia#', $dia, $template);
        $template = str_replace('#mes#', $mes, $template);
        $template = str_replace('#anio#', $anio, $template);
        
        return $template;
    }

    

}

?>
