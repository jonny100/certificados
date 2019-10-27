<?php

namespace App\Validator;

use Sonata\CoreBundle\Validator\ErrorElement;
use App\Entity\InscriptoCertificado;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class InscriptoCertificadoInlineValidator {



    public function validate(ErrorElement $errorElement, InscriptoCertificado $obj) {
 
        $certificadoEventoRequisitos = $obj->getCertificadoEvento()->getRequisitos();
        $inscriptoEventoRequisitos = $obj->getInscripto()->getRequisitos();
        

        foreach($certificadoEventoRequisitos as $certificadoR){
            $existe = false;
            foreach($inscriptoEventoRequisitos as $inscriptoR){
                $existe = false;
                if ($inscriptoR->getCertificadoEventoRequisito()->getRequisito() == $certificadoR->getRequisito()){
                     $existe = true;
                     break;
                }
            }
            if(!$existe){
                $msgx = 'Para otorgar el certificado ' . $obj . ' debe tener el requisito '.$certificadoR->getRequisito();
                $errorElement->addViolation($msgx)->end();
            }
        }
        
        
//        foreach($certificadoEventoRequisitos as $item){
//            echo($item.'-');
//        }
//        echo('<br>');
//        foreach($inscriptoEventoRequisitos as $item){
//            echo($item.'-');
//        }
//        die();
//
//        foreach ($evidencias as $evidencia) {
//            if ($evidencia->getEvidenciaId() === NULL) {
//
//                $msgx = 'Debe seleccionar una evidencia.';
//                $errorElement->addViolation($msgx)->end();
//            }
//        }
//        
//        //VALIDAR QUE AL SELECCIONAR SOLICITANTE O RESPONSABLE AL AREA DE INVESTIGACION Y LITIGACION, DEBE SELECCIONAR SUBAREA OBLIGATORIAMENTE
//        $responsables = $obj->getResponsables();
//        foreach ($responsables as $responsable){
//            if ($responsable->getArea()->getNombre() == "INVESTIGACIÓN Y LITIGACIÓN"){
//                if ($responsable->getSubarea() == NULL){
//                    $msgx = 'Debe seleccionar una subarea para el Area de INVESTIGACIÓN Y LITIGACIÓN.';
//                    $errorElement->addViolation($msgx)->end();
//                }
//            }
//        }
//        
//        if ($obj->getAreaSolicitante() != NULL){
//            if ($obj->getAreaSolicitante()->getNombre() == "INVESTIGACIÓN Y LITIGACIÓN"){
//                if ($obj->getAreaSolicitante() == NULL){
//                    $msgx = 'Debe seleccionar una subarea para el Area de INVESTIGACIÓN Y LITIGACIÓN.';
//                    $errorElement->addViolation($msgx)->end();
//                }
//            }
//        }
//        
//        if ($obj->getFechaInicio() == NULL){
//            $msgx = 'Debe seleccionar una fecha de Inicio.';
//            $errorElement->addViolation($msgx)->end();
//        }
//        
//       if ($obj->getCaso() == NULL){
//            $msgx = 'Debe seleccionar un Legajo.';
//            $errorElement->addViolation($msgx)->end();
//        } 
        
    }
}


