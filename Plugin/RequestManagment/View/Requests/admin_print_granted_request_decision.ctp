<?php
App::import('Vendor','decision');

$tcpdf = new DECISION();
$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
$tcpdf->SetAuthor("KBS Homes & Properties at http://kbs-properties.com"); 
$tcpdf->SetAutoPageBreak( false ); 
$tcpdf->setHeaderFont(array($textfont,'',40)); 
$tcpdf->xheadercolor = array(150,0,0); 
$tcpdf->uniquekey = md5($request['Request']['id']);


$tcpdf->AddPage();

$tcpdf->SetY(50);

$titre = 'technicien';
$speciality = '';

foreach($counselor['Qualification'] as $row) {
 	
 	$speciality = $row['Speciality']['name'];

 	if($row['Diplome']['DiplomeType']['grade'] == 1)
 	{
 		$titre = "ingénieur";
 		$speciality = $row['Speciality']['name'];
 		break;
 	}
}

$sexe = 'Monsieur';
$prefixe = '';
if($counselor['Counselor']['gender'] == 'female')
{
	$sexe = "Madame";
	$prefixe = 'e';
}

if($request['Request']['requester_type'] == 'legal')
{
	$tcpdf->SetFont('dejavusans', 'B', 18);
	$tcpdf->Multicell(0, 2, "Décision d’Agrément de Conseiller Agricole Privé personne moral", 0, 'C');
	$tcpdf->ln(16);
	$tcpdf->SetFont('dejavusans', '', 12);

	$tcpdf->Cell(0, 0, 'Le Ministre de l’Agriculture et de la Pêche Maritime,', 0, 1, 'L');
	$tcpdf->ln(8);	
	$tcpdf->Multicell(0, 2, "Vu la loi n° 62-12 relative à l'organisation de la profession de conseiller agricole, promulguée par le dahir n°1.14.94 du 12 rejeb 1435 (12 Mai 2014) notamment son article 4.", 0, 'L');
	$tcpdf->ln(8);	
	$tcpdf->Multicell(0, 2, "Vu le décret n°2-14-527 du 8 rebia II 1436 (29 Janvier 2015) pris pour l’application de la loi susvisée n°62-12, notamment ses  articles 3 et 5.", 0, 'L');	
	$tcpdf->ln(8);
	$tcpdf->Cell(0, 0, 'DECIDE', 0, 1, 'C');
	$tcpdf->ln(4);
	$tcpdf->SetFont('dejavusans', 'BU', 12);
	$tcpdf->Cell(0, 0, 'ARTICLE 1:', 0, 1, 'L');
	$tcpdf->ln(2);
	$tcpdf->SetFont('dejavusans', '', 12);
	$tcpdf->Multicell(0, 2, "Est agréé pour exercer la profession de Conseiller Agricole société ".strtoupper($request['Company']['name'])."  ".strtoupper($request['Company']['type'])." titulaire du Registre du Commerce n° ".$request['Company']['number']." et gérée par ".$sexe." ".strtoupper($counselor['Counselor']['first_name'])." ".strtoupper($counselor['Counselor']['last_name'])." ".$titre.", spécialité ".$speciality." titulaire de la carte d’identité nationale n° ".$counselor['Counselor']['cin'].'.', 0, 'L');
	$tcpdf->ln(8);
	$tcpdf->SetFont('dejavusans', 'BU', 12);
	$tcpdf->Cell(0, 0, 'ARTICLE 2:', 0, 1, 'L');
	$tcpdf->ln(2);
	$tcpdf->SetFont('dejavusans', '', 12);
	$tcpdf->Multicell(0, 2, "Cet agrément est accordé à l’intéressé".$prefixe." pour cinq ans selon les conditions prévues à l’article 5 du décret cité ci-dessus.", 0, 'L');
	$tcpdf->ln(8);
	$tcpdf->Cell(0, 0, 'Rabat le '. date('d/m/Y').'      ', 0, 1, 'R');
	$tcpdf->ln(4);
	$tcpdf->Multicell(0, 2, "Le Ministre de l’agriculture", 0, 'R');
	$tcpdf->ln(4);
	$tcpdf->Multicell(0, 2, "et de la pêche maritime  ", 0, 'R');
}
else
{
	$tcpdf->SetFont('dejavusans', 'B', 18);
	$tcpdf->Multicell(0, 2, "Décision d’Agrément de Conseiller Agricole Privé personne physique", 0, 'C');
	$tcpdf->ln(20);
	$tcpdf->SetFont('dejavusans', '', 12);

	$tcpdf->Cell(0, 0, 'Le Ministre de l’Agriculture et de la Pêche Maritime,', 0, 1, 'L');
	$tcpdf->ln(8);	
	$tcpdf->Multicell(0, 2, "Vu la loi n° 62-12 relative à l'organisation de la profession de conseiller agricole, promulguée par le dahir n°1.14.94 du 12 rejeb 1435 (12 Mai 2014) notamment son article 4.", 0, 'L');
	$tcpdf->ln(8);	
	$tcpdf->Multicell(0, 2, "Vu le décret n°2-14-527 du 8 rebia II 1436 (29 Janvier 2015) pris pour l’application de la loi susvisée n°62-12, notamment ses  articles 3 et 5.", 0, 'L');	
	$tcpdf->ln(8);
	$tcpdf->Cell(0, 0, 'DECIDE', 0, 1, 'C');
	$tcpdf->ln(4);
	$tcpdf->SetFont('dejavusans', 'BU', 12);
	$tcpdf->Cell(0, 0, 'ARTICLE 1:', 0, 1, 'L');
	$tcpdf->ln(2);
	$tcpdf->SetFont('dejavusans', '', 12);
	$tcpdf->Multicell(0, 2, "Est agréé  pour exercer la profession de Conseiller ".$sexe." ".strtoupper($counselor['Counselor']['first_name']." ".$counselor['Counselor']['last_name'])." ".$titre.", spécialité ".$speciality." titulaire de la carte d’identité nationale n° ".$counselor['Counselor']['cin'].'.', 0, 'L');
	$tcpdf->ln(8);
	$tcpdf->SetFont('dejavusans', 'BU', 12);
	$tcpdf->Cell(0, 0, 'ARTICLE 2:', 0, 1, 'L');
	$tcpdf->ln(2);
	$tcpdf->SetFont('dejavusans', '', 12);
	$tcpdf->Multicell(0, 2, "Cet agrément est accordé à l’intéressé".$prefixe." pour cinq ans selon les conditions prévues à l’article 5 du décret cité ci-dessus.", 0, 'L');
	$tcpdf->ln(8);
	$tcpdf->Cell(0, 0, 'Rabat le '. date('d/m/Y').'      ', 0, 1, 'R');
	$tcpdf->ln(4);
	$tcpdf->Multicell(0, 0, "Le Ministre de l’agriculture", 0, 'R');
	$tcpdf->ln(4);
	$tcpdf->Multicell(0, 0, "et de la pêche maritime  ", 0, 'R');
}


echo $tcpdf->Output('filename.pdf', 'D');