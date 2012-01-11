<?php
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
class MyPdf extends TCPDF{
  public $width;
  public $horizon = true;
  
  public function __construct($print_type="1"){
    //print_type 1が横、2が縦
    $unit = "mm";
    $format = "A3";
    if($print_type==1){
      $orientation = 'L';
      $this->horizon = true;
    }else{
      $orientation = 'P';
      $this->horizon = false;
    }
    parent::__construct($orientation, $unit, $format);
    $this->SetCreator("sampli");
    $this->SetAuthor("Nicola Asuni");
    $this->SetTitle("pdf");
    
    //headerとfooterを非表示
    $this->setPrintHeader(false);
    $this->setPrintFooter(false);
    
    // set default monospaced font
    $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    //set auto page breaks
    //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $this->SetAutoPageBreak( true, 0);
    $this->SetHeaderMargin(0);
    $this->SetMargins(8,8,8);
    
    //set image scale factor
    $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //set protection
    $this->SetProtection(array("copy"));

    $this->SetFont('arialunicid0', '', 9);
    $this->AddPage();
  }
  
  
  
}
