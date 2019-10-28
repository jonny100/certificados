<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    protected $mailer;
    
    /**
     * @Route("/verificacion", name="erificacion")
     */
    public function verificacionAction(Request $request)
    {
        
        $form = $this->createFormBuilder()//()
                ->add('dni', null, array('label' => 'DNI'))
                ->add('codigoVerificacion')
               
                ->add('validar', SubmitType::class, array('label' => 'Validar', "attr" => array('class' => "btn btn-success", 'style' => 'float:left')))
                ->getForm();
        
        $form->handleRequest($request);
        
        //Si el pedido viene con las variables cargadas en get, realizo directamente la comprobacion
        $dni = $request->request->get('dni');
        $codigoVerificacion = $request->request->get('codigo_verificacion');
        echo($dni);die();
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
                '' => '',
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
        $inscriptoCertificado = $repository->findOneBy(array('codigoVerificacion' => $codigoVerificacion));
        
        if($inscriptoCertificado){
            if($inscriptoCertificado->getInscripto()->getPersona()->getDNI() == $dni){
                return $inscriptoCertificado;
            }
        }else{
            return false;
        }
        
    }


//        $subarea_id = $request->request->get('subarea_id');
//        $em = $this->getDoctrine()->getManager();
//       
//        $usuarios = $em->getRepository('AppBundle:Tipousuario')->usuariosxsubareaSelect($subarea_id );
//        
//        return new JsonResponse($usuarios);

        
}
