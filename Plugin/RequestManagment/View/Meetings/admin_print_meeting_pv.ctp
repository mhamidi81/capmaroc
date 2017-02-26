<?php
	App::import('Vendor','pv');

	$tcpdf = new PV();
	$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
	$tcpdf->SetAuthor("KBS Homes & Properties at http://kbs-properties.com"); 
	$tcpdf->SetAutoPageBreak( false ); 
	$tcpdf->setHeaderFont(array($textfont,'',40)); 
	$tcpdf->xheadercolor = array(150,0,0); 
	
	$tcpdf->AddPage();
	$tcpdf->SetY(45);
	$tcpdf->ln(20);
	$tcpdf->SetFont('dejavusans', 'B', 13);
	$tcpdf->Multicell(0, 2, "PROCES VERBAL DE LA REUNION RELATIVE A L'EXAMEN", 0, 'C');
	$tcpdf->Multicell(0, 2, "DES DEMANDES D'AGREMENT POUR L'EXERCICE DU METIER DE", 0, 'C');
	$tcpdf->Multicell(0, 2, "CONSEILLER AGRICOLE PRIVE", 0, 'C');
	$tcpdf->ln(3);
	$tcpdf->ln(10);
	$tcpdf->SetFont('dejavusans', 'B', 12);
	$tcpdf->SetTextColor(79,129,189);
	$tcpdf->Cell(20, 0, 'Date et lieu de la réunion ', 0, 1, 'L');
	$tcpdf->Cell(190,0,'','T');
	$tcpdf->ln(5);
	$tcpdf->SetFont('dejavusans', '', 12);
	$tcpdf->SetTextColor(0,0,0);
	$tcpdf->Multicell(0, 2, "Réunion tenue au siège de la Direction de la Direction de l'Enseignement, de la Formation et de la Recherche/Ministère de l'Agriculture et de la Pêche Maritime,".date('d-m-Y à h:i:s' ,strtotime($meeting['Meeting']['event_date'])), 0, 'L');

	$tcpdf->ln(10);
	$tcpdf->SetFont('dejavusans', 'B', 12);
	$tcpdf->SetTextColor(79,129,189);
	$tcpdf->Cell(20, 0, 'Objet de la réunion ', 0, 1, 'L');
	$tcpdf->Cell(190,0,'','T');
	$tcpdf->ln(5);
	$tcpdf->SetFont('dejavusans', '', 12);
	$tcpdf->SetTextColor(0,0,0);
	$tcpdf->Multicell(0, 2, $meeting['Meeting']['name'], 0, 'L');
	$tcpdf->Multicell(0, 2, $meeting['Meeting']['description'], 0, 'L');

	$tcpdf->ln(10);
	$tcpdf->SetFont('dejavusans', 'B', 12);
	$tcpdf->SetTextColor(79,129,189);
	$tcpdf->Cell(20, 0, 'Participants ', 0, 1, 'L');
	$tcpdf->Cell(190,0,'','T');
	$tcpdf->ln(5);
	$tcpdf->SetFont('dejavusans', '', 12);
	$tcpdf->SetTextColor(0,0,0);
	$tcpdf->Multicell(0, 2, 'Voir liste jointe', 0, 'L');

	$tcpdf->ln(10);
	$tcpdf->SetFont('dejavusans', 'B', 12);
	$tcpdf->SetTextColor(79,129,189);
	$tcpdf->Cell(20, 0, 'Avis de la CNCA ', 0, 1, 'L');
	$tcpdf->Cell(190,0,'','T');
	$tcpdf->ln(5);
	$tcpdf->SetFont('dejavusans', '', 12);
	$tcpdf->SetTextColor(0,0,0);
	$tcpdf->Multicell(0, 8, 'Les avis de la CNCA sont consignées dans le tableau ci dessous.', 0, 'L');
	$tcpdf->Multicell(0, 8, 'Sur ce la séance fût levée.', 0, 'L');

	$tcpdf->ln(4);

	/***************************dîplome *************************/
	$tcpdf->AddPage();
	$tcpdf->SetY(35);
	$tcpdf->ln(20);
	$tcpdf->SetFont('dejavusans', 'B', 13);
	$tcpdf->Multicell(0, 2, "Tableau récapitulatif des travaux de la CNCA", 0, 'C');
	$tcpdf->Multicell(0, 2, "Réunion du ".date('d-m-Y' ,strtotime($meeting['Meeting']['event_date'])), 0, 'C');

	$dimensions = $tcpdf->getPageDimensions();
	$hasBorder = false; 
	$tcpdf->ln(10);
	$tcpdf->SetFont('dejavusans', 'B', 10);
	$tcpdf->MultiCell(65, 12, 'Nom & Raison social' ,'LRBT','C',0,0,null,null, false,0,null,null,12, 'M');
	$tcpdf->MultiCell(30, 12, 'Personnalité juridique' ,'LRBT','C',0,0,null,null, true,0,null,null,12, 'M');
	$tcpdf->MultiCell(30, 12, 'Avis de la commission' ,'LRBT','C',0,0,null,null, true,0,null,null,12, 'M');
	$tcpdf->MultiCell(65, 12, 'Observations' ,'LRBT','C',0,0,null,null, true,0,null,null,12, 'M');	 
	$tcpdf->ln(12);
	$tcpdf->SetFont('dejavusans', '', 10);
	
	foreach($meeting_requests as $row) {
		
		$rowcount = 0;
		
		if($row['Request']['requester_type'] == 'legal')
		{
			$requester = $row['Company']['name'] . ' ' .$row['Company']['type'];
		}
		else
		{
			$requester = $row['Counselor']['first_name'] . ' ' .$row['Counselor']['last_name'];
		}

		//work out the number of lines required
		$rowcount = max($tcpdf->getNumLines($row['MeetingsRequest']['description'], 65),$tcpdf->getNumLines($requester, 65));
	 
		$startY = $tcpdf->GetY();
	 
		if (($startY + $rowcount * 6) + $dimensions['bm'] > ($dimensions['hk'])) {
			//this row will cause a page break, draw the bottom border on previous row and give this a top border
			//we could force a page break and rewrite grid headings here
			if ($hasborder) {
				$hasborder = false;
			} else {
				$tcpdf->Cell(190,0,'','T'); //draw bottom border on previous row
				$tcpdf->Ln();
			}
			$borders = 'LTR';
		} elseif ((ceil($startY) + $rowcount * 6) + $dimensions['bm'] == floor($dimensions['hk'])) {
			//fringe case where this cell will just reach the page break
			//draw the cell with a bottom border as we cannot draw it otherwise
			$borders = 'LRB';	
			$hasborder = true; //stops the attempt to draw the bottom border on the next row
		} else {
			//normal cell
			$borders = 'LRB';
		}
	 
		//now draw it
		$tcpdf->MultiCell(65,$rowcount * 6,$requester, $borders,'L',0,0,null,null, true,0,null,null, $rowcount * 6,'M');
		$tcpdf->MultiCell(30,$rowcount * 6,($row['Request']['requester_type'] !== 'natural')? 'Morale' : 'Physique' ,$borders,'L',0,0,null,null, true,0,null,null, $rowcount * 6,'M');
		$tcpdf->MultiCell(30,$rowcount * 6,($row['MeetingsRequest']['Judgment']['alias'] == 'favorable')? 'Favorable' : 'Défavorable',$borders,'L',0,0,null,null, true,0,null,null, $rowcount * 6,'M');
		$tcpdf->MultiCell(65,$rowcount * 6,$row['MeetingsRequest']['description'],$borders,'L',0,0,null,null, true,0,null,null, $rowcount * 6,'M');
	 
		$tcpdf->Ln();
	}
 	$tcpdf->Cell(190,0,'','T');  //last bottom border
 	/***************************end dîplome *************************/

	/***************************dîplome *************************/
	$tcpdf->AddPage();
	$tcpdf->SetY(35);
	$tcpdf->ln(20);
	$tcpdf->SetFont('dejavusans', 'B', 13);
	$tcpdf->Multicell(0, 2, "Liste des participants CNCA", 0, 'C');
	$tcpdf->Multicell(0, 2, "Réunion du ".date('d-m-Y' ,strtotime($meeting['Meeting']['event_date'])), 0, 'C');

	$dimensions = $tcpdf->getPageDimensions();
	$hasBorder = false; 
	$tcpdf->ln(10);
	$tcpdf->SetFont('dejavusans', 'B', 10);
	$tcpdf->MultiCell(30, 8, 'Structures' ,'LRBT','C',0,0,null,null, true,0,null,null, 8,'M');
	$tcpdf->MultiCell(60, 8, 'Nom et prénom' ,'LRBT','C',0,0,null,null, true,0,null,null, 8,'M');
	$tcpdf->MultiCell(60, 8, 'Qualité' ,'LRBT','C',0,0,null,null, true,0,null,null, 8,'M');
	$tcpdf->MultiCell(40, 8, 'Emargement' ,'LRBT','C',0,0,null,null, true,0,null,null, 8,'M');	 
	$tcpdf->ln(8);
	$tcpdf->SetFont('dejavusans', '', 10);

	$meeting['MeetingsUser'] = Hash::sort(Hash::insert($meeting['MeetingsUser'], count($meeting['MeetingsUser']), array('User' => $meeting['User'])), '{n}.User.role_id', 'desc');

	foreach($meeting['MeetingsUser'] as $row) {
		
		$rowcount = 0;
		
		$user = $row['User']['last_name'] . ' ' .$row['User']['first_name'];

		//work out the number of lines required
		$rowcount = max($tcpdf->getNumLines($row['User']['Service']['abreviation'], 30),$tcpdf->getNumLines($user, 60));
	 
		$startY = $tcpdf->GetY();
	 
		if (($startY + $rowcount * 6) + $dimensions['bm'] > ($dimensions['hk'])) {
			//this row will cause a page break, draw the bottom border on previous row and give this a top border
			//we could force a page break and rewrite grid headings here
			if ($hasborder) {
				$hasborder = false;
			} else {
				$tcpdf->Cell(190,0,'','T'); //draw bottom border on previous row
				$tcpdf->Ln();
			}
			$borders = 'LTR';
		} elseif ((ceil($startY) + $rowcount * 6) + $dimensions['bm'] == floor($dimensions['hk'])) {
			//fringe case where this cell will just reach the page break
			//draw the cell with a bottom border as we cannot draw it otherwise
			$borders = 'LRB';	
			$hasborder = true; //stops the attempt to draw the bottom border on the next row
		} else {
			//normal cell
			$borders = 'LRB';
		}

	 	$role = ($row['User']['Role']['alias'] == 'director')? 'Président' : 'Membre';

		//now draw it
		$tcpdf->MultiCell(30,$rowcount * 6,$row['User']['Service']['abreviation'], $borders,'L',0,0,null,null, true,0,null,null, $rowcount * 6,'M');
		$tcpdf->MultiCell(60,$rowcount * 6, $user ,$borders,'L',0,0,null,null, true,0,null,null, $rowcount * 6,'M');
		$tcpdf->MultiCell(60,$rowcount * 6, $row['User']['Service']['abreviation'].' : '.$role ,$borders,'L',0,0,null,null, true,0,null,null, $rowcount * 6,'M');
		$tcpdf->MultiCell(40,$rowcount * 6, ' ' ,$borders,'L',0,0,null,null, true,0,null,null, $rowcount * 6,'M');
	 
		$tcpdf->Ln();
	}
 	$tcpdf->Cell(190,0,'','T');  //last bottom border
 	/***************************end dîplome *************************/

echo $tcpdf->Output('filename.pdf', 'D');