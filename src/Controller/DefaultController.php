<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Application\ReportBundle\Report\ReportPDF;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    protected $mailer;
    
    /**
     * @Route("/verificacion", name="erificacion")
     */
    public function verificacionAction(Request $request)
    {
        
        $form = $this->createFormBuilder(null, array('attr' => array('class' => 'form-verificacion')))//()
                ->add('dni', null, array('label' => 'DNI', "attr" => array('class' => 'form-control', 'placeholder' => 'DNI')))
                ->add('codigoVerificacion', null, array('label' => 'Codigo de Verificación', "attr" => array('class' => 'form-control', 'placeholder' => 'Codigo de Verificación')))
               
                ->add('validar', SubmitType::class, array('label' => 'Validar', "attr" => array('class' => "btn btn-lg btn-primary btn-block")))
                ->getForm();
        
        $form->handleRequest($request);
        
        //Si el pedido viene con las variables cargadas en get, realizo directamente la comprobacion
        $dni = $request->query->get('dni');
        $codigoVerificacion = $request->query->get('codigo_verificacion');
        
        $directo = false;
        if(isset($dni) && isset($codigoVerificacion)){
            $directo = true;
        }
        if (($form->isSubmitted() && $form->isValid()) || $directo) { // si el formulario es valido
            if(!$directo){
                $params = $form->getData();
                $dni = $params['dni'];
                $codigoVerificacion = $params['codigoVerificacion'];
            }
            
            $inscriptoCertificado = $this->validarCertificado($dni, $codigoVerificacion);
               
            if($inscriptoCertificado){
                $validado = true;
            }else{
                $validado = false;
            }
            
            
            if($validado){
                $vars = array(
                'action' => 'verificacion',
                'apellidoYNombre' => $inscriptoCertificado->getInscripto()->getPersona(),
                'dni' => $dni,
                'cursoNombre' => $inscriptoCertificado->getCertificadoEvento()->getEvento(),
                'resolucion' => null !== $inscriptoCertificado->getCertificadoEvento()->getEvento()->getResolucion() ? $inscriptoCertificado->getCertificadoEvento()->getEvento()->getResolucion() : '',
                'horas' => null !== $inscriptoCertificado->getCertificadoEvento()->getEvento()->getHoras() ? $inscriptoCertificado->getCertificadoEvento()->getEvento()->getHoras() : '',
                'fechaObt' => $inscriptoCertificado->getFechaObt(),
                'form' => $form->createView(),
                );
                
                return $this->render('Default\verificacionCorrecta.html.twig', $vars);
            }else{
                return $this->render('Default\verificacionIncorrecta.html.twig');
            }
        } 

        $vars = array(
            'action' => 'verificacion',
            'form' => $form->createView(),
        );
            
        return $this->render('Default\verificacion.html.twig', $vars);
        
    }
    
    public function validarCertificado($dni, $codigoVerificacion){
        $repository = $this->getDoctrine()->getRepository('App:InscriptoCertificado');
        $inscriptoCertificado = $repository->findOneBy(array('codigoVerificacion' => $codigoVerificacion, 'estado' => 2));
        
        if($inscriptoCertificado){
            if($inscriptoCertificado->getInscripto()->getPersona()->getDNI() == $dni){
                return $inscriptoCertificado;
            }
        }else{
            return false;
        }
        
    }
        
}
