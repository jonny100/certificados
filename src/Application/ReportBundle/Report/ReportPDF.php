<?php
namespace App\Application\ReportBundle\Report;
use \TCPDF;
class ReportPDF extends TCPDF {

    public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->setupReport();
     }
     
    protected function setupReport() {
        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Sistema de Gestion Certificados - Escuela para la Innovacion Educativa');
        $this->SetSubject('PDF Report');
        $this->SetKeywords('report, sgrt');
       
        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
     
        // set margins
        //PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT en mm
        $this->SetMargins(20, 15, 20);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
    }
     
     //Page header
    public function Header() {
        // Logo
        $image_file = __DIR__.'/../../../AppBundle/Resources/public/imgs/ministeriopublico.png';
        //$this->setHeaderData($image_file, 150, '', '');// 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->Image($image_file, 30, 20, 110, 27, '', '', '', true, 150);// 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        //
        // Set font
        //$this->SetFont('helvetica', '', 8);
        // Title
        //$txt = 'Sistema de Gestión de Legajos';
        //$this->Cell(0, 10, $image_file, 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }
    
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $pages_txt = 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages();
        $today = new \DateTime();
        $txt = sprintf('Escuela para la Innovacion Educativa - %s - %s',
            $today->format('d/m/Y h:m'), $pages_txt);
        $this->Cell(0, 10, $txt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
   
    public function setupPageParams($params) {
        $key = 'page_margin_top';
        if(array_key_exists($key, $params))
            $this->setTopMargin($params[$key]);
        $key = 'page_margin_left';
        if(array_key_exists($key, $params))
            $this->setLeftMargin($params[$key]);
        $key = 'page_margin_right';
        if(array_key_exists($key, $params))
            $this->setRightMargin($params[$key]);
        $key = 'page_margin_bottom';
        $margin_bottom = 0;
        if(array_key_exists($key, $params)) {
            $margin_bottom = $params[$key];
            $this->setTopMargin($params[$key]);
        }
        $key = 'page_format';
        if(array_key_exists($key, $params))
            $this->setPageFormat($params[$key]);
        $key = 'page_orientation';
        if(array_key_exists($key, $params))
            $this->setPageOrientation($params[$key], True, $margin_bottom);
    }
}