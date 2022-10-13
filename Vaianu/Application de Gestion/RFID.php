<?php include('connectBDD.php'); //Connexion à la base de données ?>
<?php

$date = date('Y-m-d'); // Récupération de la date d'aujourd'hui
$requete = $bdd->query("SELECT h_depart FROM course where dateURSE='$date'"); // requête pour récupérer l'heure de départ de la course
$recupHeure_depart = $requete->fetch();
$heure_depart = $recupHeure_depart['h_depart'];
$heure_envoyerRFID_Aux_Stations = DiminuerDeCinqMinutes($heure_depart); // Diminution heure départ de 5 minutes pour envoyer aux stations heure courante et n°RFID 5 minutes avant départ
$heure = date('H:i:s'); // heure courante pour ensuite comparaison avec heure départ et heure diminuer

/*----------------# Enregistrement des temps passage des coureurs dans la base de données avec heure passage reçu #-------------------------*/
if ($heure >= $heure_depart && isset($_GET['_numRFID']) && isset($_GET['_heurePass']) && isset($_GET['_numSta'])) // si heure départ atteint et toutes les variables sont présents dans l'URL
{
	$numRFID = $_GET['_numRFID']; // réception du numRFID transmis par la station
	$requete = $bdd->query("SELECT idURSE, idREUR FROM dossard WHERE numRFID='$numRFID'"); // identification du coureur
	$recup = $requete->fetch();
	$idREUR = $recup['idREUR']; // on récupère clé primaire idREUR du coureur pour clé étrangère dans la table chrono
	$idURSE = $recup['idURSE']; // pareil pour la course 
	$heurePassage = $_GET['_heurePass']; // réception de l'heure de passage du coureur
	$numSta = $_GET['_numSta']; // réception du numéro de station
	
	if ($numSta == 1) // association de la station 1 au champ temps de départ de la table chrono
		$bdd->exec("INSERT INTO chrono(idURSE,idREUR,t_depart) VALUES('$idURSE','$idREUR', '$heurePassage')");
	else if ($numSta == 2) // association de la station 2 au champ temps intermédiaire
		$bdd->exec("UPDATE chrono SET t_inter='$heurePassage' WHERE idREUR='$idREUR'");
	else
	{
		$verif = $bdd->query("SELECT t_inter FROM chrono WHERE idREUR='$idREUR'"); // requête pour vérifier que le coureur est bien passé par la station intermédiaire
		$recupVerif = $verif->fetch();
		if ($numSta == 3 && $recupVerif['t_inter'] != '00:00:00')// association de la station 3 au champ temps arrivée et vérification si coureur est bien passé par station intermédiaire
			$bdd->exec("UPDATE chrono SET t_arrivee='$heurePassage' WHERE idREUR='$idREUR'");
	}
}

/*----------------# Enregistrement des temps passage des coureurs dans la base de données avec heure passage local #-------------------------*/
else if ($heure >= $heure_depart && isset($_GET['_numRFID']) && isset($_GET['_numSta'])) // si heure départ atteint et toutes les variables sont présents dans l'URL
{
	$numRFID = $_GET['_numRFID']; // réception du numRFID transmis par la station
	$requete = $bdd->query("SELECT idURSE, idREUR FROM dossard WHERE numRFID='$numRFID'"); // identification du coureur
	$recup = $requete->fetch();
	$idURSE = $recup['idURSE']; // on récupère clé primaire idURSE du coureur pour clé étrangère dans la table chrono
	$idREUR = $recup['idREUR']; // pareil pour la course
	$heurePassage = $heure;
	$numSta = $_GET['_numSta']; // réception du numéro de station
	
	if ($numSta == 1) // association de la station 1 au champ temps de départ de la table chrono
		$bdd->exec("INSERT INTO chrono(idURSE,idREUR,t_depart) VALUES('$idURSE','$idREUR', '$heurePassage')");
	else if ($numSta == 2) // association de la station 2 au champ temps intermédiaire
		$bdd->exec("UPDATE chrono SET t_inter='$heurePassage' WHERE idREUR='$idREUR'");
	else
	{
		$verif = $bdd->query("SELECT t_inter FROM chrono WHERE idREUR='$idREUR'"); // requête pour vérifier que le coureur est bien passé par la station intermédiaire
		$recupVerif = $verif->fetch();
		if ($numSta == 3 && $recupVerif['t_inter'] != '00:00:00')// association de la station 3 au champ temps arrivée et vérification si coureur est bien passé par station intermédiaire
			$bdd->exec("UPDATE chrono SET t_arrivee='$heurePassage' WHERE idREUR='$idREUR'");
	}
}

/*------------------# Envoie de l'heure courante et des numéros RFID #-------------------------------*/
else if ($heure >= $heure_envoyerRFID_Aux_Stations) // si heure pour envoyer les numéros RFID aux stations est atteint ou dépassé
{
	$requete = $bdd->query("SELECT numRFID FROM dossard, course WHERE course.dateURSE='$date' && dossard.idURSE=course.idURSE");
	echo date('H:i:s').' '; // alors envoie de l'heure courante aux stations
	while ($donnees = $requete->fetch()){
		echo $donnees['numRFID'].' '; // envoie des numéros RFID aux stations
	}
}

/*-----------#  Fonction pour diminuer l'heure de départ de 5 minutes #---------------*/
function DiminuerDeCinqMinutes($heure)
{
	$h = $heure[0].$heure[1];   // on récupére l'heures de départ
	$m = $heure[3].$heure[4];   // on récupére les minutes de départ
	for ($i=0; $i < 5; $i++)
	{
		if ($m == 00) // si la minutes est égal à 00
		{
			$m = 59; // alors on lui affecte 59, puisque -1 n'existe pas comme minute
			$h--; // on soustrait l'heure par 1
			if ($h >= 0 && $h <= 9) // si heure soustrait est entre 0 et 9
				$h = '0'.$h; // alors on ajoute un 0 devant la nouvelle heure pour comparaison car après soustraction on a qu'un seul chiffre
		}
		else // sinon 
		{
			$m--; // on soustrait que les minutes
		}
	}
	if ($m >= 0 && $m <= 9) // si minute soustrait est entre 0 et 9
	{
		$m = '0'.$m; // alors on ajoute un 0 devant la nouvelle minute pour comparaison car après soustraction on a qu'un seul chiffre et sa nuit 
	}
	return $h.':'.$m.':00';
}

?>
