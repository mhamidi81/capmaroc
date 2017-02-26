<?php
App::import('Vendor','badge');

$tcpdf = new BADGE('p', 'pt', array(240, 160), true, 'UTF-8', false);
$tcpdf->SetPrintFooter(false);
$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
$tcpdf->SetAuthor("KBS Homes & Properties at http://kbs-properties.com"); 
$tcpdf->SetAutoPageBreak( false ); 
$tcpdf->setHeaderFont(array($textfont,'',40)); 
$tcpdf->xheadercolor = array(150,0,0); 

$tcpdf->AddPage();

$tcpdf->SetY(5);

$titre = 'Technicien';

foreach($counselor['Qualification'] as $row) {
 	
 	$speciality = $row['Speciality']['name'];

 	if($row['Diplome']['DiplomeType']['grade'] == 1)
 	{
 		$titre = "Ingénieur";
 		break;
 	}
}

$sexe = 'Monsieur';

if($counselor['Counselor']['gender'] == 'female')
{
	$sexe = "Madame";
}

if($titre = 'Technicien')
{
	$color = array(0,176,93);
}
else
{
	$color = array(255,0,23);
}

$tcpdf->SetFont('dejavusans', '', 11);

if($request['Request']['requester_type'] == 'legal')
{
	$image_file = Configure::read('company_document_abs_path').$request['Company']['logo'];
	$image_file = str_replace('capmaroc', 'capwebsite',  $image_file );
    $extension = (strpos(strtolower($request['Company']['logo']), '.png') !== false)? 'PNG': 'JPG';

	$tcpdf->Image($image_file, '',65, 0,50, $extension, 'T', 'C', true, 200, 'C', false, false, 0, false, false, false);

	$tcpdf->Rect(0,215,160,32,'F',null,$color);

	$tcpdf->SetY(135);
	$tcpdf->Multicell(0, 0, strtoupper($request['Company']['name'])."  ".strtoupper($request['Company']['type']), 0, 'C');
	$tcpdf->ln(4);
	$tcpdf->Cell(0, 0, $titre.' Agricole', 0, 1, 'C');
	$tcpdf->ln(12);
	$tcpdf->Cell(0, 0, 'N° '.$request['Request']['number'], 0, 1, 'C');
	$tcpdf->SetTextColor(255,255,255);
	$tcpdf->Cell(0, 70, 'Conseiller Agricole privé', 0, 1, 'C');

}
else
{	
	
	$image_file = Configure::read('counselor_photo_abs_path').$counselor['Counselor']['image'];
	$image_file = str_replace('capmaroc', 'capwebsite',  $image_file );
    $extension = (strpos(strtolower($counselor['Counselor']['image']), '.png') !== false)? 'PNG': 'JPG';
	$tcpdf->Image($image_file, '',60, 0,70, $extension, 'T', 'C', false, 200, 'C', false, false, 0, false, false, false);

	$tcpdf->Rect(0,215,160,32,'F',null,$color);

	$tcpdf->SetY(135);
	//$tcpdf->SetTextColor(245,245,245);
	$tcpdf->Multicell(0, 0, strtoupper($counselor['Counselor']['first_name']." ".$counselor['Counselor']['last_name']), 0, 'C');
	$tcpdf->ln(4);
	$tcpdf->Cell(0, 0, $titre.' Agricole', 0, 1, 'C');
	$tcpdf->ln(12);
	$tcpdf->Cell(0, 0, 'N° '.$request['Request']['number'], 0, 1, 'C');
	$tcpdf->SetTextColor(255,255,255);
	$tcpdf->Cell(0, 43, 'Conseiller Agricole privé', 0, 1, 'C');
}
	$tcpdf->SetPrintHeader(false);
	$tcpdf->SetMargins(5, 5, 5, true);
	$tcpdf->AddPage();
	$tcpdf->SetFont('dejavusans', 'B', 8);
	$tcpdf->SetTextColor(0,0,0);
	$tcpdf->SetY(15);
	$bg_img = '../webroot/img/badge_bg_without_trone.png';
    $tcpdf->Image($bg_img, 0, 0, 154, 240, '', '', '', false, 300, '', false, false, 0); 
    $tmp_image = '../webroot/img/tmp.png';
    $tcpdf->Multicell(0, 0, 'Carte strictement Personnelle', 0, 'L');
    $tcpdf->SetFont('dejavusans', '', 8);
    $tcpdf->ln(4);
    $tcpdf->Multicell(0, 0, 'En cas de perte ou du vol le titulaire doit aviser immédiatement le M.A.D.R.P.M', 0, 'L');
    $tcpdf->ln(4);
    $tcpdf->SetX(5);
    $tcpdf->Multicell(0, 0, "Toute personne trouvant la présente carte est priée de bien vouloir l'adresser sous pli non affranchi à : Ministère de l'Agriculture et des pêches Maritimes, Direction de l'Enseignement, de la Formation et de la Recherche, Station D'bagh - Avenu Hassan II B.P 607 Rabat", 0, 'L');
    $data = file_get_contents("http://localhost/capmaroc/barcode.php?text=".md5($request['Request']['id']).'&size=100');
    file_put_contents($tmp_image, $data);
   $tcpdf->Image($tmp_image, 3,200, 250,38, 'PNG', '', 'L', false, false, 600, false, false, '' , true, false, false);
    unlink($tmp_image); 


echo $tcpdf->Output('filename.pdf', 'D');