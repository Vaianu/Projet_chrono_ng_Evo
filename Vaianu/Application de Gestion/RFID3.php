<?php include('connectBDD.php'); ?> <!-- Connexion à la base de données -->
<?php
if (isset($_GET['_numRFID']) && isset($_GET['_heurePass']) && isset($_GET['_numSta']))
{
	$numRFID = $_GET['_numRFID']; // réception du numRFID transmis par la station
	$requete = $bdd->query("SELECT idURSE, idREUR FROM dossard WHERE numRFID='$numRFID'"); // identification du coureur
	$recup = $requete->fetch();
	$idURSE = $recup['idURSE'];
	$idREUR = $recup['idREUR'];
	$heurePassage = $_GET['_heurePass']; // réception de l'heure de passage du coureur
	$numSta = $_GET['_numSta']; // réception du numéro de station
	
	if ($numSta == 1)// association de la station 1 au champ temps de départ de la table chrono
		$bdd->exec("INSERT INTO chrono(idURSE,idREUR,t_depart) VALUES('$idURSE','$idREUR', '$heurePassage')");
	if ($numSta == 2)// association de la station 2 au champ temps intermédiaire
		$bdd->exec("UPDATE chrono SET t_inter='$heurePassage' WHERE idREUR='$idREUR'");
	$verif = $bdd->query("SELECT t_inter FROM chrono WHERE idREUR='$idREUR'"); // pour vérifier que le coureur est bien passé
	$recupVerif = $verif->fetch();													// par la station intermédiaire
	if ($numSta == 3 && $recupVerif['t_inter'] != '00:00:00')// association de la station 3 au champ temps arrivée
		$bdd->exec("UPDATE chrono SET t_arrivee='$heurePassage' WHERE idREUR='$idREUR'");
}
else
{
	echo date('G:i:s').' '; // envoie de l'heure courante aux stations
	$_date = date('Y-m-d'); // Récupération de la date d'aujourd'hui
	$reponse = $bdd->query("SELECT numRFID FROM dossard, course WHERE course.dateURSE='$_date' && dossard.idURSE=course.idURSE");
	while ($donnees = $reponse->fetch()){
		echo $donnees['numRFID'].' '; // envoie des numéros RFID
	}
}
?>

