<?php

App::import('Vendor', 'tcpdf', array('file' => 'tcpdf/tcpdf.php'));
App::import('Vendor', 'phpqrcode'.DS.'qrlib');

class DECISION  extends TCPDF { 

    var $xheadertext  = ''; 
    var $xheadercolor = array(0,0,200); 
    var $xfootertext  = 'Copyright 2015 © Ministre de l’Agriculture et de la Pêche Maritime.'; 
    var $xfooterfont  = PDF_FONT_NAME_MAIN ; 
    var $xfooterfontsize = 8 ; 
    var $uniquekey = -1;

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
        $trone_image = '../webroot/img/logo_trone.jpg';
        $this->Image($trone_image, 80,5, 50,27, 'JPG', '', 'L', false, 200, '', false, false, 0, false, false, false);

        /*
        $logo_image = '../webroot/img/logo.png';
        $this->Image($logo_image, 5,8, 80,50, 'PNG', '', 'L', false, false, 600, false, false, 'LTRB' , true, false, false);
        */
        $bg_img = '../webroot/img/logo_bg.png';
        $this->Image($bg_img, 30, 90, 150, 140, '', '', '', false, 300, '', false, false, 0);  
    } 

    /** 
    * Overwrites the default footer 
    * set the text in the view using 
    * $fpdf->xfootertext = 'Copyright Â© %d YOUR ORGANIZATION. All rights reserved.'; 
    */ 
    function Footer() 
    { 
        $this->SetY(-20); 
        $footer_image = '../webroot/img/footer.png';
        $this->Image($footer_image, 0, $this->GetY()-10, 200,30, 'PNG', '', 'L', false, 200, '', false, false, 0, false, false, false);
    } 
} ?>