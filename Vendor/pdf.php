<?php
App::import('Vendor','tcpdf/tcpdf');
class PDF  extends TCPDF
{
    var $xheadertext  = 'PDF created using CakePHP and this';
    var $xheadercolor = array(0,0,200);
    var $xfootertext  = 'Copyright  All rights reserved.';
    var $xfooterfont  = PDF_FONT_NAME_MAIN ;
    var $xfooterfontsize = 9 ;
	
    public function __construct($demande) {
	$this->demande=$demande;
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    }

    /**
    * Overwrites the default header
    * set the text in the view using
    *    $fpdf->xheadertext = 'YOUR ORGANIZATION';
    * set the fill color in the view using
    *    $fpdf->xheadercolor = array(0,0,100); (r, g, b)
    * set the font in the view using
    *    $fpdf->setHeaderFont(array('YourFont','',fontsize));
    */
    function Header()
    {
	$this->SetY(3);
	$this->SetFont('dejavusans', '', 10);
	$this->setTempRTL('R');
	$image_file = '../webroot/img/fm6e.jpg';
	//$this->SetXY(20,10);
	$this->Image($image_file, 60,3, 85,18, 'JPG', '', 'C', false, 300, '', false, false, 0, false, false, false);
	//$image_file = '../webroot/img/logo.jpg';
	//$this->SetXY(20,10);
	//$this->Image($image_file, 60,3, 85,18, 'JPG', '', 'C', false, 300, '', false, false, 0, false, false, false);
	        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);
        $img_file = '../webroot/img/logo_bg.jpg';
        $this->Image($img_file, 30, 90, 150, 140, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
    $this->SetY(30);
   //$this->Ln(0.5);
    $this->SetFont('times', 'B', 13);
    $this->Cell(0, 0, 'DEMANDE D’ACHAT N° '.$this->demande["Demande"]["id"], 0, 1, 'C');

    $this->SetY(40);
    $this->SetFont('times', 'B', 13);
    $this->Cell(0, 0, 'Date               : ', 0, 1, 'L');
    $this->SetXY(45, $this->GetY() - 5.5);
    $this->SetFont('times', '', 12);
    $this->Cell(0, 0, $this->demande["Demande"]["created"], 0, 1, '');
	
    $this->Ln(2);
    $this->SetFont('times', 'B', 13);
    $this->Cell(0, 0, 'Demandeur  : ', 0, 1, 'L');
    $this->SetXY(45, $this->GetY() - 5.5);
    $this->SetFont('times', '', 12);
    $this->Cell(0, 0, $this->demande["User"]["Service"]["name"].'/'.@$this->demande["User"]["name"], 0, 1, '');
    
    $this->Ln(2);
    $this->SetFont('times', 'B', 13);
    $this->Cell(0, 0, 'Fournisseur : ', 0, 1, 'L');
    $this->SetXY(45, $this->GetY() - 5.5);
    $this->SetFont('times', '', 12);
    $this->Cell(0, 0, $this->demande["Fournisseur"]["nom"], 0, 1, '');
    
	$this->Ln(2);
    $this->SetFont('times', 'B', 13);
    $this->Cell(0, 0, 'Objet            :', 0, 1, 'L');
    $this->SetXY(45, $this->GetY() - 5.5);
    $this->SetFont('times', '', 12);
    $this->Cell(0, 0, $this->demande["Demande"]["title"], 0, 1, '');
	
    $this->Ln(2);
    $this->SetFont('times', 'B', 13);
    $this->Cell(0, 0, 'Imputation  : ', 0, 1, 'L');
    $this->SetXY(45, $this->GetY() - 5.5);
    $this->SetFont('times', '', 12);
    $this->Cell(0, 0,$this->demande["Demande"]["programme"], 0, 1, '');
    

    
    $this->Ln(3);
	//$this->Rect(16,248,106, 24,'',array('TR' => array('width' => 1),'BL' => array('width' => 0)), 0);
    $this->SetX(16);

$this->SetFont('times', '', 12);
    $this->SetXY(16, 80);
	$this->Cell(85, 10, 'Désignation', array('LRTB' => array('width' => 0.2)), 0, 'C');
	$this->Cell(45, 10, 'Quantité', array('LRTB' => array('width' => 0.2)), 0, 'C');
    $this->Cell(45, 10, 'Prix TTC', array('LRTB' => array('width' => 0.2)), 0, 'C');
	$this->SetY(90);
	}

    /**
    * Overwrites the default footer
    * set the text in the view using
    * $fpdf->xfootertext = 'Copyright Â© %d YOUR ORGANIZATION. All rights reserved.';
    */
    function Footer()
    { 
	$this->SetXY(15,240);   
$this->SetFont('times', 'B', 13);
$this->MultiCell(50, 5, ' ', array('LRTB' => array('width' => 0.2)), 'C','',0);
  $this->MultiCell(60, 5, 'Demandeur', array('LRTB' => array('width' => 0.2)), 'C','',0);
	 $this->MultiCell(60, 5, 'Secrétaire Générale', array('LRTB' => array('width' => 0.2)), 'C','',1);
	 $this->MultiCell(50, 7, 'Date', array('LRB' => array('width' => 0.2)), 'C','',0);
  $this->MultiCell(60, 7, ' ', array('LRB' => array('width' => 0.2)), 'C','',0);
	 $this->MultiCell(60, 7, '', array('LRB' => array('width' => 0.2)), 'C','',1);
	 	 $this->MultiCell(50, 20, 'Visas', array('LRB' => array('width' => 0.2)), 'C','',0);
  $this->MultiCell(60, 20, ' ', array('LRB' => array('width' => 0.2)), 'C','',0);
	 $this->MultiCell(60, 20, '', array('LRB' => array('width' => 0.2)), 'C','',1);
	/*  $this->Cell(50, 10, ' ', array('LRTB' => array('width' => 0.2)), 0, 'C','',0);
   $this->Cell(60, 10, 'Demandeur', array('LRTB' => array('width' => 0.2)), 0, 'C','',0);
	$this->Cell(60, 10, 'Secrétaire Générale', array('LRTB' => array('width' => 0.2)), 0, 'C','',1);
	$this->Cell(50, 10, '', array('LRTB' => array('width' => 0.2)), 0, 'C','',0);
    $this->Cell(60, 10, 'Demandeur', array('LRTB' => array('width' => 0.2)), 0, 'C','',0);
	$this->Cell(60, 10, 'Secrétaire Générale', array('LRTB' => array('width' => 0.2)), 0, 'C','',1);
	
    $this->SetXY(16,248);
    $this->SetFont('times', '', 12);
    $this->Cell(106, 24, " Cachet Visas :", array('TR' => array('width' => 0.2)), 0,'L','','','','','','T');
    $this->SetFont('times', 'B', 14);
    $this->Cell(30, 8, " Total H.T   : ", array('T' => array('width' => 0.2)), 0);
    
    $this->Cell(42, 8, number_format($this->demande_total_ht, 2, ',', ' ')."  DH    ", array('T' => array('width' => 0.2)), 2,'R');
	$this->SetX($this->GetX()-30);
	$this->Cell(30, 8, " TVA ".($this->demande_tva*100)."%   : ", array('T' => array('width' => 0.2)), 0);
    $this->Cell(42, 8,number_format($this->demande_tva*$this->demande_total_ht, 2, ',', ' ')."  DH    ", array('T' => array('width' => 0.2)), 2,'R');
	$this->SetX($this->GetX()-30);
	$this->Cell(30, 8, " Total TTC   : ", array('T' => array('width' => 0.2)), 0);
    $this->Cell(42, 8,number_format($this->demande_total_ht+$this->demande_tva*$this->demande_total_ht, 2, ',', ' ')."  DH    ", array('T' => array('width' => 0.2)), 1,'R');
	//$this->SetX($this->GetX()-30);
	$this->ln(3);
        $this->SetFont($this->xfooterfont,'',$this->xfooterfontsize);
		$this->Cell(0,4, 'Km 3,2 - Route de Zaërs,Avenue Mohammed VI, rue El Madani Ibn El Houssaini - Rabat - B.P 5679',0,1,'C');
        //$this->ln();
		$this->Cell(0,2, 'Tél : LG 05 37 65 88 44 - Fax : 05 37 65 55 31 - E-mail : environnement@fm6e.org - Site Web : www.fm6.org',0,1,'C');
		//$this->SetFont('courier', '', 11);
		$this->SetFont('dejavusans', '', 8);
		$this->Cell(0,0, 'كلم 3.2 ـ طريق زعير شارع محمد السادس زنقة المدني إبن الحسيني ـ الرباط  ص . ب . 5679',0,1,'C');
$this->Cell(0,3, 'الهاتف : (خ . م)  05.37.65.88.44 ـ الفاكس :05.37.65.55.31 ـ بريد الأنترنيت : org.fm6e@environnement ـ موقع الأنترنيت  : org.fm6.www','',1,'C');		
	*/}
}
?>
