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
        $inscriptoCertificado = $repository->findOneBy(array('codigoVerificacion' => $codigoVerificacion));
        
        if($inscriptoCertificado){
            if($inscriptoCertificado->getInscripto()->getPersona()->getDNI() == $dni){
                return $inscriptoCertificado;
            }
        }else{
            return false;
        }
        
    }

    /**
     * @Route("/imprimirmasivo", name="imprimirmasivo")
     */
    public function imprimirMasivoAction(Request $request){
        $form = $this->createFormBuilder()//()
                ->add('evento', EntityType::class, array('query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                                        return $repository->createQueryBuilder('e')
                                        ->andWhere('e.estado = 1')
                                        ->orderBy('e.descripcion');
                                        },
                                        'class' => 'App\Entity\Evento',
                                        'required' => true,
                                         )                                                  
                     )
                ->add('certificadoEvento', EntityType::class, array('query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                                        return $repository->createQueryBuilder('e')
                                        ->orderBy('e.certificado');
                                        },
                                        'class' => 'App\Entity\CertificadoEvento',
                                        'required' => true,
                                        'label' => 'Certificado'
                                         )                                                  
                     )
               
                ->add('generar', SubmitType::class, array('label' => 'Generar Certificados', "attr" => array('class' => "btn btn-lg btn-primary btn-block", 'target' => '_blank')))
                ->getForm();
        
        $form->handleRequest($request);
        
        
        if (($form->isSubmitted() && $form->isValid())) { // si el formulario es valido
  
            $params = $form->getData();
            $evento = $params['evento'];
            $certificadoEvento = $params['certificadoEvento'];

            $em = $this->getDoctrine()->getManager();
            //Obtengo todos los inscriptos certificados que cumplan el filtro
            $inscriptoCertificados = $em->getRepository('App:InscriptoCertificado')
                    ->createQueryBuilder('ic')
                    ->leftJoin('ic.inscripto', 'i')
                    ->where('i.evento = :evento and ic.certificadoEvento = :certificadoEvento and i.estado = 1 and ic.estado = 1 ')
                    ->setParameter('evento', $evento)
                    ->setParameter('certificadoEvento', $certificadoEvento)
                    ->getQuery()->getResult();
            
               
            if($inscriptoCertificados){
                $validado = true;
            }else{
                $validado = false;
            }
            
            
            if($validado){
                if ($form->get('generar')->isClicked()) {
                    $this->generarCertificadosMasivoAction($inscriptoCertificados, $request);
                }               
            }else{
                //mensaje no existen datos
            }
        } 

        $vars = array(
            'action' => 'imprimirmasivo',
            'form' => $form->createView(),
        );
            
        return $this->render('Default\imprimirMasivo.html.twig', $vars);
    }
    
    public function generarCertificadosMasivoAction($inscriptoCertificados, Request $request){
        
        $pdf = new ReportPDF(); 
        
        foreach ($inscriptoCertificados as $inscriptoCertificado){
            
            $pdf->AddPage('L');

            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(0);

            $pdf->SetFont("FreeSerif", "", 16);

            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // -- set new background ---

            // get the current page break margin
            $bMargin = $pdf->getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = $pdf->getAutoPageBreak();
            // disable auto-page-break
            $pdf->SetAutoPageBreak(false, 0);
            // set bacground image
            for ($i = 1; $i <= $pdf->getNumPages(); $i++) {
                $pdf->setPage($i);
                $img_file = $this->getParameter('kernel.project_dir') . '/public/images/imagencertificado.jpg';
                $pdf->Image($img_file, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0);
            }
            // restore auto-page-break status
            $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            $pdf->setPageMark();



            //set style for barcode
            $style = array(
                'border' => 1,
                'vpadding' => 'auto',
                'hpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                'bgcolor' => false, //array(255,255,255)
                'module_width' => 1, // width of a single module in points
                'module_height' => 1 // height of a single module in points
            );

            $qr = $request->getHttpHost() . '/verificacion?dni=' . $inscriptoCertificado->getInscripto()->getPersona()->getDNI() . '&codigo_verificacion=' . $inscriptoCertificado->getCodigoVerificacion();

            $text = $this->reemplazarVariables($inscriptoCertificado);

            $pdf->writeHTMLCell(0,0, 80, 30, $text, 0, 0, 0, true,"L", 0);
            $pdf->write2DBarcode( $qr, 'QRCODE,L', 240, 113, 40, 40, $style, 'N');
            $pdf->writeHTMLCell(0,0, 239, 152, $inscriptoCertificado->getCodigoVerificacion());
        }
        
        $file = $pdf->Output('certificado.pdf', 'I');

        $response = new Response($file);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'filename="certificado.pdf"');
        return $response;
            
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
