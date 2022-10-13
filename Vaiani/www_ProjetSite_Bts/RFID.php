<?php include('connectBDD.php'); //Connexion à la base de données ?>
<?php

$date = date('Y-m-d'); // Récupération de la date d'aujourd'hui
$requete = $bdd->query("SELECT h_depart FROM course where dateURSE='$date'"); // requête pour récupérer l'heure de départ de la course
$recupHeure_depart = $requete->fetch();
$heure_depart = $recupHeure_depart['h_depart'];
$heure_envoyerRFID_Aux_Stations = DiminuerDeCinqMinutes($heure_depart); // Diminution heure départ de 5 minutes pour envoyer aux stations heure courante et n°RFID 5 minutes avant départ
$heure = date('H:i:s'); // on récupère  vite l'heure actuelle dans une variable

/*----------------# Enregistrement des temps passage des coureurs dans la base de données avec heure passage reçu des stations #-------------------------*/
if ($heure >= $heure_depart && isset($_GET['_numRFID']) && isset($_GET['_heurePass']) && isset($_GET['_numSta'])) // si heure départ atteint et toutes les variables sont présents dans l'URL
{
	$numRFID = $_GET['_numRFID']; // réception du numRFID transmis par la station
	$requete = $bdd->query("SELECT dossard.idURSE, dossard.idREUR FROM dossard, course WHERE dossard.idURSE=course.idURSE AND dateURSE='$date' AND numRFID='$numRFID'"); // identification du coureur
	$recup = $requete->fetch();
	$idREUR = $recup['idREUR']; // on récupère clé primaire idREUR du coureur pour clé étrangère dans la table chrono
	$idURSE = $recup['idURSE']; // pareil pour l'idURSE de la course
	$heurePassage = $_GET['_heurePass']; // réception de l'heure de passage du coureur
	$numSta = $_GET['_numSta']; // réception du numéro de station
	
	if ($numSta == 1) // association de la station 1 au champ temps de départ de la table chrono
		$bdd->exec("INSERT INTO chrono(idURSE,idREUR,t_depart) VALUES('$idURSE','$idREUR', '$heurePassage')"); // requête d'ajout d'un nouveau enregistrement avec temps départ du coureur dans la table chrono
	else if ($numSta == 2) // association de la station 2 au champ temps intermédiaire
		$bdd->exec("UPDATE chrono SET t_inter='$heurePassage' WHERE idREUR='$idREUR' AND t_inter='00:00:00'"); // requête de mise à jour du champ temps intermédiaire du coureur
	else
	{
		if ($numSta == 3)// association de la station 3 au champ temps arrivée
			$bdd->exec("UPDATE chrono SET t_arrivee='$heurePassage' WHERE idREUR='$idREUR' AND t_inter!='00:00:00' AND t_arrivee='00:00:00'"); // requête vérification coureur est bien passé par station 2
	}																																		     // si oui, mise à jour du champ temps arrivée du coureur
}

/*----------------# Enregistrement des temps passage des coureurs dans la base de données avec heure passage local #-------------------------*/
else if ($heure >= $heure_depart && isset($_GET['_numRFID']) && isset($_GET['_numSta'])) // si heure départ atteint et toutes les variables sont présents dans l'URL
{
	$numRFID = $_GET['_numRFID']; // réception du numRFID transmis par la station
	$requete = $bdd->query("SELECT dossard.idURSE, dossard.idREUR FROM dossard, course WHERE dossard.idURSE=course.idURSE AND dateURSE='$date' AND numRFID='$numRFID'"); // identification du coureur
	$recup = $requete->fetch();
	$idREUR = $recup['idREUR']; // on récupère clé primaire idREUR du coureur pour clé étrangère dans la table chrono
	$idURSE = $recup['idURSE']; // pareil pour l'idURSE de la course
	$heurePassage = $heure;
	$numSta = $_GET['_numSta']; // réception du numéro de station
	
	if ($numSta == 1) // association de la station 1 au champ temps de départ de la table chrono
		$bdd->exec("INSERT INTO chrono(idURSE,idREUR,t_depart) VALUES('$idURSE','$idREUR', '$heurePassage')"); // requête d'ajout d'un nouveau enregistrement avec temps départ du coureur dans la table chrono
	else if ($numSta == 2) // association de la station 2 au champ temps intermédiaire
		$bdd->exec("UPDATE chrono SET t_inter='$heurePassage' WHERE idREUR='$idREUR' AND t_inter='00:00:00'"); // requête de mise à jour du champ temps intermédiaire du coureur
	else
	{
		if ($numSta == 3)// association de la station 3 au champ temps arrivée
			$bdd->exec("UPDATE chrono SET t_arrivee='$heurePassage' WHERE idREUR='$idREUR' AND t_inter!='00:00:00' AND t_arrivee='00:00:00'"); // requête vérification coureur est bien passé par station 2
	}																																		     // si oui, mise à jour du champ temps arrivée du coureur
}

/*------------------# Envoie de l'heure courante et des numéros RFID #-------------------------------*/
else if ($heure >= $heure_envoyerRFID_Aux_Stations) // si heure pour envoyer les numéros RFID aux stations est atteint ou dépassé
{
	$requete = $bdd->query("SELECT numRFID FROM dossard, course WHERE course.dateURSE='$date' && dossard.idURSE=course.idURSE");
	echo '@'.date('H:i:s').' '; // alors envoie de l'heure courante aux stations
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
