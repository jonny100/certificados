<?php

declare(strict_types=1);

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use App\Application\ReportBundle\Report\ReportPDF;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

final class InscriptoCertificadoAdminController extends CRUDController
{
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws NotFoundHttpException
     * @abstract Funcion para generar la caratula en pdf
     */
    public function certificadoAction(Request $request){    
            $object = $this->admin->getSubject();
            $id = $request->get($this->admin->getIdParameter());
            $inscriptoCertificado = $this->admin->getObject($id);
            
            if (!$object) {
                throw new NotFoundHttpException(sprintf('No se pudo encontrar el objeto correspondiente a esta identificación : %s', $id));
            }
            
            $pdf = new ReportPDF(); 
            
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
              
            $json = $object->getCodigoQR();//'{ "datos": [{ "id": "'.$object->getId().'", "clase": "caso", "descripcion": "'.$object->getNumeroLegajo(). ' - ' .$object->getAnioLegajo().'-'.$object.'"  }] }';
          
            $text = $this->reemplazarVariables($inscriptoCertificado);

//            $text = 'texto'; //$object->getTextoCaratula();
            $pdf->writeHTMLCell(0,0, 80, 30, $text, 0, 0, 0, true,"L", 0);
            $pdf->write2DBarcode( $json, 'QRCODE,L', 240, 113, 40, 40, $style, 'N');
            $pdf->writeHTMLCell(0,0, 239, 152, $inscriptoCertificado->getCodigoVerificacion());
            $file = $pdf->Output('certificado.pdf', 'S');
            
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
        
        $fechaactual = new \DateTime("now");
        $dia = $fechaactual->format('d');
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        $mes = ucwords(iconv('ISO-8859-2', 'UTF-8', strftime("%B", $fechaactual->getTimestamp())));
        $anio = $fechaactual->format('Y');
        
        $template = $obj->getCertificadoEvento()->getTemplate()->getCodigo();  
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
