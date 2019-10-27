<?php

declare(strict_types=1);

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use App\Application\ReportBundle\Report\ReportPDF;
use Symfony\Component\HttpFoundation\Response;

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
                $img_file = dirname(__FILE__).'/../Resources/public/images/imagencertificado.jpg';
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

            $text = $inscriptoCertificado->getCertificadoEvento()->getTemplate()->getCodigo();  

//            $text = 'texto'; //$object->getTextoCaratula();
            $pdf->writeHTMLCell(0,0, 20, 40, $text, 0, 0, 0, true,"C", 0);
            $pdf->write2DBarcode( $json, 'QRCODE,L', 240, 115, 40, 40, $style, 'N');
            $file = $pdf->Output('certificado.pdf', 'S');
            
            $response = new Response($file);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'filename="certificado.pdf"');
            return $response;
    }

}
