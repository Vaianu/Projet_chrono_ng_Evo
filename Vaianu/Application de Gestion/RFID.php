<?php include('connectBDD.php'); //Connexion à la base de données ?>
<?php

date_default_timezone_set('Europe/Paris');
$date = date('Y-m-d');
$requete = $bdd->query("SELECT h_depart FROM course where dateURSE='$date'"); // requête pour récupérer l'heure de départ de la course
$recupHeure_depart = $requete->fetch();
$heure_depart = isset($recupHeure_depart['h_depart']) ? $recupHeure_depart['h_depart'] : null;
$heure_envoyerRFID_Aux_Stations = $heure_depart != null ? DiminuerDeCinqMinutes($heure_depart) : null; // Diminution heure départ de 5 minutes pour envoyer aux stations heure courante et n°RFID 5 minutes avant départ
$heure = date('H:i:s'); // on récupère  vite l'heure actuelle dans une variable

/*----------------# Enregistrement des temps passage des coureurs dans la base de données avec heure passage reçu des stations ou heure locale #-------------------------*/
if ($heure_depart != null && $heure >= $heure_depart && isset($_GET['_numRFID']) && isset($_GET['_numSta'])) // si heure départ atteint et toutes les variables sont présents dans l'URL
{
	$numRFID = $_GET['_numRFID']; // réception du numRFID transmis par la station
	$requete = $bdd->query("SELECT dossard.idURSE, dossard.idREUR FROM dossard, course WHERE dossard.idURSE=course.idURSE AND dateURSE='$date' AND numRFID='$numRFID'"); // identification du coureur
	$recup = $requete->fetch();
	$idREUR = $recup['idREUR'];
	$idURSE = $recup['idURSE'];
	$heurePassage = isset($_GET['_heurePass']) ? $_GET['_heurePass'] : $heure; // réception de l'heure de passage du coureur
	$numSta = $_GET['_numSta']; // réception du numéro de station
	
	// requête pour vérifier que le coureur n'est pas encore passé par la station 1, 2 ou 3
	$sRequete = '';
	switch($numSta) {
		case 1:
			$sRequete = 'SELECT 1 FROM chrono WHERE idREUR='.$idREUR.';';
			break;
		case 2:
			$sRequete = 'SELECT 1 FROM chrono WHERE idREUR='.$idREUR.' AND t_inter="00:00:00";';
			break;
		case 3:
			$sRequete = 'SELECT 1 FROM chrono WHERE idREUR='.$idREUR.' AND t_arrivee="00:00:00" AND t_inter!="00:00:00";';
			break;
		default:
			$sRequete = 'select null from dual;';
	}
	$requete = $bdd->query($sRequete);
	$recup = $requete->fetch();
	
	// association de la station 1 au champ temps de départ de la table chrono
	if ($numSta == 1 && !isset($recup['1'])) {
		$bdd->exec("INSERT INTO chrono(idURSE,idREUR,t_depart) VALUES('$idURSE','$idREUR', '$heurePassage')"); // requête d'ajout d'un nouveau enregistrement avec temps départ du coureur dans la table chrono
		echo 'Enregistrement station 1 pour idREUR '.$idREUR;
	}
	else if ($numSta == 2 && isset($recup['1']) && $recup['1'] == 1) { // association de la station 2 au champ temps intermédiaire
		// Maj t_inter et tempsInter du coureur actu
		$bdd->exec("UPDATE chrono SET t_inter='$heurePassage', tempsInter=TIMEDIFF('$heurePassage', t_depart) WHERE idREUR='$idREUR'");
		calculerDifference($idREUR, $idURSE, $numSta, $heurePassage, $bdd);
		echo 'Enregistrement station 2 pour idREUR '.$idREUR;
	}
	else
	{
		// association de la station 3 au champ temps arrivée
		if ($numSta == 3 && isset($recup['1']) && $recup['1'] == 1) {
			// Maj t_arrivee et tempsFinal du coureur actu
			$bdd->exec("UPDATE chrono SET t_arrivee='$heurePassage', tempsFinal=TIMEDIFF('$heurePassage', t_depart) WHERE idREUR='$idREUR'");
			calculerDifference($idREUR, $idURSE, $numSta,$heurePassage, $bdd);
			echo 'Enregistrement station 3 pour idREUR '.$idREUR;
		}
	}
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

/*-----------#  Fonction pour calculer la différrence du coureur avec le champion #---------------*/
function calculerDifference($idREURActu, $idURSE, $numSta, $heurePassageREURActu, $bdd)
{
	// Recup tempsInter ou tempsFinal du coureur actu
	$sRequete = $numSta == 2 ? 'SELECT tempsInter' : 'SELECT tempsFinal';
	$sRequete2 = ' FROM chrono WHERE idREUR='.$idREURActu;
	$sRequete = $sRequete.$sRequete2;
	$requete = $bdd->query($sRequete);
	$reponse = $requete->fetch();

	//Maj de la difference du coureur actu avec champion
	if($numSta == 2) {
		$tempsInterREURActu = $reponse['tempsInter']; // récup tempsInter REUR actu
		$requete = $bdd->query("SELECT tempsInter, idREUR FROM chrono WHERE idURSE='$idURSE' AND idREUR!='$idREURActu' AND tempsInter!='00:00:00' ORDER BY tempsInter LIMIT 0,1");
		$reponse = $requete->fetch();
		$tempsInterChampion = isset($reponse['tempsInter']) ? $reponse['tempsInter'] : null; // si premier à passer la station intermédiaire, on mets null (pas de calcul)
		if($tempsInterChampion != null && $tempsInterChampion < $tempsInterREURActu){
			echo 'Toujours même champion <br />';
			echo 'tempsInterchampion: '.$tempsInterChampion.'&nbsp tempsInterREUR actu:'.$tempsInterREURActu;
			
			// Maj temps difference inter coureur actuelle avec champion
			$bdd->exec("UPDATE chrono SET difference=TIMEDIFF('$tempsInterREURActu', '$tempsInterChampion') WHERE idREUR='$idREURActu'");
		}
		else if($tempsInterChampion != null && $tempsInterChampion > $tempsInterREURActu) { // si coureur actuelle a fait mieux que le champion actuelle, il passe champion
			echo 'Changement champion <br />';
			echo 'tempsInterAncienchampion: '.$tempsInterChampion.'&nbsp tempsInterREUR actu(Nouveau champion):'.$tempsInterREURActu;
			
			$idREURAncienChampion = $reponse['idREUR'];
			// Maj temps difference inter de l'ancien champion et des autres coureurs par rapport au nouveau champion
			$bdd->exec("UPDATE chrono SET difference=TIMEDIFF(tempsInter, '$tempsInterREURActu') WHERE idREUR!='$idREURActu' AND t_inter!='00:00:00' AND t_inter<='$heurePassageREURActu'");
		}
	}
	else { // $numSta == 3; Maj tempsFinal et de la difference du coureur avec champion
		$tempsFinalREURActu = $reponse['tempsFinal']; // récup tempsFinal REUR actu
		$requete = $bdd->query("SELECT tempsFinal, idREUR FROM chrono WHERE idURSE='$idURSE' AND idREUR!='$idREURActu' AND tempsFinal!='00:00:00' ORDER BY tempsFinal LIMIT 0,1");
		$reponse = $requete->fetch();
		$tempsFinalChampion = isset($reponse['tempsFinal']) ? $reponse['tempsFinal'] : null; // si premier à passer la ligne d'arrivée, on mets null (pas de calcul)
		if($tempsFinalChampion != null && $tempsFinalChampion < $tempsFinalREURActu){
			echo 'Toujours même champion <br />';
			echo 'tempsFinalchampion: '.$tempsFinalChampion.'&nbsp tempsFinalREUR actu:'.$tempsFinalREURActu;
			
			// Maj temps difference arrivee coureur actuelle avec champion
			$bdd->exec("UPDATE chrono SET difference=TIMEDIFF('$tempsFinalREURActu', '$tempsFinalChampion') WHERE idREUR='$idREURActu'");
		}
		else if($tempsFinalChampion != null && $tempsFinalChampion > $tempsFinalREURActu) { // si coureur actuelle a fait mieux que le champion actuelle, il passe champion
			echo 'Changement champion <br />';
			echo 'tempsFinalAncienchampion: '.$tempsFinalChampion.'&nbsp tempsFinalREUR actu(Nouveau champion):'.$tempsFinalREURActu;
			
			$idREURAncienChampion = $reponse['idREUR'];
			// Maj temps difference arrivee de l'ancien champion et des autres coureurs par rapport au nouveau champion
			$bdd->exec("UPDATE chrono SET difference=TIMEDIFF(tempsFinal, '$tempsFinalREURActu') WHERE idREUR!='$idREURActu' AND t_arrivee!='00:00:00' AND t_arrivee<='$heurePassageREURActu'");
		}
		else if($tempsFinalChampion == null) { // on remets à 0 la diff si coureur courant est le premier à franchir la ligne d'arrivée
			$bdd->exec("UPDATE chrono SET difference='0' WHERE idREUR='$idREURActu' AND t_arrivee!='00:00:00'");
		}
	}
}

?>