<?php

declare(strict_types=1);

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Application\ReportBundle\Report\ReportPDF;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Application\ToolsBundle\Form\Type\DependentFilteredEntityType;
use App\Entity\CertificadoEvento;


final class EventoAdminController extends CRUDController
{
    public function imprimirMasivoAction(Request $request){
        $dependant_entities = $this->container->getParameter('application_tools.dependent_filtered_entities');
        $subscriber = $this->get('application_tools.dependent_filtered_entity_subscriber');

        $form_options = $dependant_entities['certificadoEvento_by_evento']['form_options'];
        $form_options = array_merge($form_options, array('required'=>true, 'attr'=>array('data-sonata-select2'=>'false')));        
        $subscriber->addField('certificado', $form_options); 
        
        
        $form = $this->createFormBuilder()//()
                ->add('evento', EntityType::class, array('query_builder' => function (\Doctrine\ORM\EntityRepository $repository) {
                                        return $repository->createQueryBuilder('e')
                                        ->andWhere('e.estado = 1')
                                        ->orderBy('e.descripcion');
                                        },
                                        'class' => 'App\Entity\Evento',
                                        'required' => true
                                         )                                                  
                     )
                ->add('certificado', DependentFilteredEntityType::class, $form_options)
                
                
                ->add('generar', SubmitType::class, array('label' => 'Generar Certificados', "attr" => array('class' => "btn btn-lg btn-primary btn-block", 'target' => '_blank')))
                ->addEventSubscriber($subscriber)
                ->getForm();
                                        
                $form->handleRequest($request);
        
        
        if (($form->isSubmitted() && $form->isValid())) { // si el formulario es valido
  
            $params = $form->getData();
            $evento = $params['evento'];
            $certificadoEvento = $params['certificado'];

            $em = $this->getDoctrine()->getManager();
            //Obtengo todos los inscriptos certificados que cumplan el filtro
            $inscriptoCertificados = $em->getRepository('App:InscriptoCertificado')
                    ->createQueryBuilder('ic')
                    ->leftJoin('ic.inscripto', 'i')
                    ->where('i.evento = :evento and ic.certificadoEvento = :certificadoEvento and i.estado = 1 and ic.estado = 2 ')
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
                $this->addFlash('sonata_flash_info', 'No existen certificados para el filtro seleccionado.');
            }
        } 

        $vars = array(
            'action' => 'imprimirmasivo',
            'form' => $form->createView(),
        );
            
        return $this->render('EventoAdmin\imprimirMasivo.html.twig', $vars);
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
//            for ($i = 1; $i <= $pdf->getNumPages(); $i++) {
//                $pdf->setPage($i);
//                $img_file = $this->getParameter('kernel.project_dir') . '/public/images/imagencertificado.jpg';
//                $pdf->Image($img_file, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0);
//            }
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

            $qr = $request->getSchemeAndHttpHost() . '/verificacion?dni=' . $inscriptoCertificado->getInscripto()->getPersona()->getDNI() . '&codigo_verificacion=' . $inscriptoCertificado->getCodigoVerificacion();

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
