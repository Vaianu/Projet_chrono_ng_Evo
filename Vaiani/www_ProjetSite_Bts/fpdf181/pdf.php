<?php

session_start(); // Pour récupérer le choix de la course de la page "AfficheResultat.php"
include('../connectBDD.php'); //Connexion à la base de données
require('fpdf.php'); // appels de la classe "fpdf"
 
 class PDF extends FPDF
 {
	function header() {
		//$this->Image('couff.jpg',0,0);
		
		include('../connectBDD.php');
		if (isset($_SESSION['choixCourse']))
		{
			$choixCourse = $_SESSION['choixCourse']; // Récupération du choix de la course
			$requeteInfosCourse = $bdd->query("SELECT * FROM course WHERE idURSE='$choixCourse'");
			$data = $requeteInfosCourse->fetch();
			
			$this->SetFont('Arial','B',14);
			//$this->Cell(276,5,'RESULTAT COURSE',0,0,'C');
			$this->Cell(0,5,'RESULTAT COURSE',1,2,'C');
			$this->Ln();
			$this->SetFont('Times','',12);
			$this->Cell(276,10,$data['nomURSE'].'  '.$data['lieuURSE'].'  '.$data['distanceURSE'].'  '.date('d/m/Y', strtotime($data['dateURSE'])),0,0,'C'); // Information course
			//$this->Cell(276,10,$data['nomURSE'].'  '.$data['lieuURSE'].'  '.$data['distanceURSE'].'  '.date('d/m/Y', strtotime($data['dateURSE'])),1,2,'C');
			//$this->Ln(20);
			$this->Ln();
		}
	}
	
	function footer() {
		$this->SetY(-15);
		$this->SetFont('Arial','',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function headerTable() {
		$this->SetX(55);
		$this->SetFont('Times','B',12);
		$this->Cell(25,10,'Classement',1,0,'C');
		$this->Cell(40,10,'Nom',1,0,'C');
		$this->Cell(40,10,utf8_decode('Prénom'),1,0,'C');
		$this->Cell(40,10,'Dossard',1,0,'C');
		$this->Cell(40,10,'Temps',1,0,'C');
		$this->Ln();
	}
	
	function AfficheTableau($db) {
		$class = 1;
		if (isset($_SESSION['choixCourse'])){
			$choixCourse = $_SESSION['choixCourse']; // Récupération du choix de la course
			
			$this->SetFont('Times','',12);
			$requeteArrivant = $db->query("SELECT coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, chrono.tempsFinal FROM (((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idREUR=coureur.idREUR && dossard.idURSE=course.idURSE) WHERE course.idURSE='$choixCourse' && chrono.t_arrivee!='00:00:00' ORDER BY chrono.tempsFinal");
			while ($data = $requeteArrivant->fetch()) {
				$this->SetX(55);
				$this->Cell(25,10,$class++,1,0,'C');
				$this->Cell(40,10,utf8_decode($data['nomREUR']),1,0,'C');
				$this->Cell(40,10,utf8_decode($data['prenomREUR']),1,0,'C');
				$this->Cell(40,10,$data['numSARD'],1,0,'C');
				$this->Cell(40,10,$data['tempsFinal'],1,0,'C');
				$this->Ln();
			}
		
			$requeteAbandon = $db->query("SELECT coureur.nomREUR, coureur.prenomREUR, dossard.numSARD FROM (((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idURSE=course.idURSE && dossard.idREUR=coureur.idREUR) WHERE course.idURSE='$choixCourse' && chrono.t_arrivee='00:00:00'");
			while ($data = $requeteAbandon->fetch()) {
				$this->SetX(55);
				$this->Cell(25,10,$class++,1,0,'C');
				$this->Cell(40,10,utf8_decode($data['nomREUR']),1,0,'C');
				$this->Cell(40,10,utf8_decode($data['prenomREUR']),1,0,'C');
				$this->Cell(40,10,$data['numSARD'],1,0,'C');
				$this->Cell(40,10,'Abandon',1,0,'C');
				$this->Ln();
			}
		}
	}
 }
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L','A4',0);
$pdf->SetDisplayMode(80); // Zoom
$pdf->headerTable();
$pdf->AfficheTableau($bdd);
$pdf->Output('I', 'resultat.pdf', true);
?>