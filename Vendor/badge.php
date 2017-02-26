<?php

App::import('Vendor', 'tcpdf', array('file' => 'tcpdf/tcpdf.php'));
App::import('Vendor', 'phpqrcode'.DS.'qrlib');

class BADGE  extends TCPDF { 

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
        $bg_img = '../webroot/img/badge_bg.png';
        $this->Image($bg_img, 0, 0, 154, 240, '', '', '', false, 300, '', false, false, 0); 
    } 

    /** 
    * Overwrites the default footer 
    * set the text in the view using 
    * $fpdf->xfootertext = 'Copyright Â© %d YOUR ORGANIZATION. All rights reserved.'; 
    */ 
    function Footer() 
    { 
    } 
} ?>