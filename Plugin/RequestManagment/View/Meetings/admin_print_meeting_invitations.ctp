<?php
	App::import('Vendor','invitation');

	$tcpdf = new INVITATION();
	$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
	$tcpdf->SetAuthor("KBS Homes & Properties at http://kbs-properties.com"); 
	$tcpdf->SetAutoPageBreak( false ); 
	$tcpdf->setHeaderFont(array($textfont,'',40)); 
	$tcpdf->xheadercolor = array(150,0,0); 
	
	foreach($meeting['MeetingsUser'] as $i => $meeting_user) {
		$tcpdf->AddPage();
		$tcpdf->SetY(45);
		$tcpdf->SetFont('dejavusans', '', 12);
		$tcpdf->Cell(0, 0, 'N° '.$meeting['Meeting']['id'].'/'.date('Y').'/DEFR/DV/SSR', 0, 0, 'L');
		$tcpdf->Cell(0, 0, 'Rabat, le '.date('d-m-Y'), 0, 1, 'R');
		$tcpdf->SetFont('dejavusans', 'B', 14);
		$tcpdf->ln(10);
		$tcpdf->Multicell(0, 2, "LE DIRECTEUR DE L’ENSEIGNEMENT,", 0, 'C');
		$tcpdf->Multicell(0, 2, "DE LA FORMATION ET DE LA RECHERCHE", 0, 'C');
		$tcpdf->Multicell(0, 2, "A", 0, 'C');
		$tcpdf->ln(3);
		$tcpdf->SetFont('dejavusans', '', 12);
		$tcpdf->Multicell(0, 2, $meeting_user['User']['first_name'].' '.$meeting_user['User']['last_name']. ' ('.$meeting_user['User']['Service']['abreviation'].')', 0, 'C');
		$tcpdf->ln(10);
		$tcpdf->SetFont('dejavusans', 'B', 12);
		$tcpdf->Cell(20, 0, 'Objet : ', 0, 0, 'L');
		$tcpdf->SetFont('dejavusans', '', 12);
		$tcpdf->Cell(0, 0, 'Réunion de la Commission Nationale de Conseil', 0, 0, 'L');
		$tcpdf->ln(10);
		$tcpdf->Multicell(0, 2, "J’ai l’honneur de vous demander de bien vouloir participer  à la réunion de la Commission Nationale de Conseil Agricole qui aura lieu le".date('d-m-Y h:i:s' ,strtotime($meeting['Meeting']['event_date'])), 0, 'L');
		$tcpdf->Cell(180, 0, "Cette réunion portera sur l'examen des dossiers ci-dessous :", 0, 1, 'L');
		$tcpdf->ln(4);
		
		$dimensions = $tcpdf->getPageDimensions();
		foreach($meeting_requests as $key => $meeting_request) {
		 
			$startY = $tcpdf->GetY();
		 
			if (($startY +  8) + $dimensions['bm'] > ($dimensions['hk'])) {
				//this row will cause a page break, draw the bottom border on previous row and give this a top border
				//we could force a page break and rewrite grid headings here
				$tcpdf->SetPrintHeader(false);
				$tcpdf->SetPrintFooter(false);
				$tcpdf->AddPage();
			}
	        $bullet = '../webroot/img/bullet.png';
	        $tcpdf->Image($bullet, 12,$startY, 5,5, 'PNG', '', 'L', false, 200, '', false, false, 0, false, false, false);	 
			//now draw it
			$tcpdf->SetX(20);
			$requester = '';
			$tcpdf->SetFont('dejavusans', '', 11);
			
			if($meeting_request['Request']['requester_type'] == 'legal')
			{
				$requester = $meeting_request['Company']['name'] . ' ' .$meeting_request['Company']['type'];
			}
			else
			{
				$requester = $meeting_request['Counselor']['first_name'] . ' ' .$meeting_request['Counselor']['last_name'];
			}

			$tcpdf->MultiCell(0, 8,' N° '. strtoupper($meeting_request['Request']['number']. ' - '.$requester) ,0,'L',0,0);
			$tcpdf->Ln();
		}

		$tcpdf->ln(20);
		$tcpdf->Cell(0, 0, "Vous remerciant par avance de votre présence à la réunion de la CNCA.", 0, 1, 'L');
		$tcpdf->Cell(0, 0, "Nous vous prions d’agréer, Madame, Monsieur, l’expression de nos salutations distinguées.", 0, 1, 'L');
	}
echo $tcpdf->Output('filename.pdf', 'D');