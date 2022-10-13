<?php include('connectBDD.php'); ?> <!-- Connexion à la base de données -->

<?php

echo '
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="votretemps.css" />
	<link rel="icon" type="image/x-icon" href="dossier/logo.JPG" />
	<title>Votre Temps - chrono_ng</title>
</head>
<body>';


if (isset($_GET['idURSE']) && isset($_GET['numSARD']))
{
	$numSARD = $_GET['numSARD'];
	$idURSE = $_GET['idURSE'];
	
	$requeteTotalParticiapnt = $bdd->query("SELECT COUNT(*) AS total FROM chrono WHERE idURSE='$idURSE'");
	$totalParticipant = $requeteTotalParticiapnt->fetch();
	
	$requete = $bdd->query("SELECT nomREUR, prenomREUR, numSARD, tempsFinal FROM (((chrono INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN dossard ON dossard.idURSE=course.idURSE AND dossard.idREUR=coureur.idREUR) WHERE chrono.idURSE='$idURSE' AND chrono.tempsFinal!='00:00:00' ORDER BY chrono.tempsFinal");
	$classement = 1;
	while ($donnees = $requete->fetch()) {
		
		if ($donnees['numSARD'] == $numSARD) // Quand c'est égale affiche son résultat
		{
			$temps = $donnees['tempsFinal'];
			$temps[2] = 'h'; // Pour afficher 10h00m00s au lieu de 10:00:00
			$temps[5] = 'm';
			$temps = $temps.'s';
			echo '<p>Bravo '.htmlspecialchars($donnees['nomREUR']).' '.htmlspecialchars($donnees['prenomREUR']).', votre temps est de <span>'.$temps.'</span> <br>classement: '.$classement.'/'.$totalParticipant['total'].'</p>'; 				
		}
		$classement++;
	}
}
echo '</body>';

?>

