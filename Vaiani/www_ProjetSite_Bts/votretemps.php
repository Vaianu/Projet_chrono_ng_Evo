<?php include('CalculTemps.php'); ?> <!-- Inclusion de la classe Temps pour le calcul des temps -->
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
	
	/* -- Calcul des temps finals -- */
	$requeteFinish = $bdd->query("SELECT coureur.idREUR, chrono.t_depart, chrono.t_arrivee FROM ((chrono INNER JOIN course ON chrono.idURSE=course.idURSE)INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) WHERE chrono.idURSE='$idURSE' && chrono.t_arrivee!='00:00:00' && chrono.tempsFinal='00:00:00' ORDER BY chrono.t_arrivee");	

	while ($resultat = $requeteFinish->fetch()) {

		$heure_depart = $resultat['t_depart'][0].$resultat['t_depart'][1];   // On récupére l'heure de départ
		$min_depart = $resultat['t_depart'][3].$resultat['t_depart'][4];     // On récupére les minutes de départ
		$seconde_depart = $resultat['t_depart'][6].$resultat['t_depart'][7]; // On récupére les secondes de départ
	
		$heure_arrivee = $resultat['t_arrivee'][0].$resultat['t_arrivee'][1]; 
		$min_arrivee = $resultat['t_arrivee'][3].$resultat['t_arrivee'][4];
		$seconde_arrivee = $resultat['t_arrivee'][6].$resultat['t_arrivee'][7];

		$tempsDepart = new Temps($heure_depart, $min_depart, $seconde_depart); // Instanciation de la classe Temps
		$tempsArrivee = new Temps($heure_arrivee, $min_arrivee, $seconde_arrivee);
		$tempsTotal = new Temps();
		$tempsTotal->calculTemps($tempsArrivee, $tempsDepart); // Calcul du temps Final
	
	/* --------#  Mis à jour dans la table "chrono" pour le "tempsFinal"  #--------- */
	$recupTempsBdd = $tempsTotal->getTempsBdd();
	$idREUR = $resultat['idREUR'];
	
	$bdd->exec("UPDATE chrono SET tempsFinal='$recupTempsBdd' WHERE idREUR='$idREUR'");	
	}
	
	
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

