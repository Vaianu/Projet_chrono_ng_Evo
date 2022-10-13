<?php include('connectBDD.php'); ?>

<?php

date_default_timezone_set('Europe/Paris');
$_date = date('Y-m-d');
$_heure = date('H:i:s');
// Récupére la course ou l'heure de départ est inférieur à l'heure actuelle
$SelectCourse = $bdd->query("SELECT * FROM course WHERE dateURSE='$_date' && h_depart<='$_heure' ORDER BY h_depart DESC LIMIT 0,1");
$reponse = $SelectCourse->fetch();

/* ----- # Calcul de l'heure de fin de course # ------ */ 
global $heure_fin;
$heure_d = isset($reponse['h_depart']) ? $reponse['h_depart']: null; // Récupération de l'heure de départ
if(!empty($heure_d)) {
	$heure_fin = $heure_d[0].$heure_d[1]; // Récupération que de l'heure
	$heure_fin = $heure_fin + 6; // Rajout de 6 heure en plus à l'heure de départ pour avoir l'heure de fin
	$heure_fin = $heure_fin.':00:00';
	
	if (strlen($heure_fin) < 8) {// Si heure est égale à 9:00:00 alors rajoute un 0 => 09:00:00
		$heure_fin = '0'.$heure_fin;
	}
}

////////// Formulaire Mini-Chat //////////
if (!empty($reponse['nomURSE']) && $_heure < $heure_fin) {
	echo '<form action="" method="post">
			<input type="text" name="pseudo" placeholder="PSEUDO" value="';
			if (isset($_POST['pseudo'])) echo $_POST['pseudo'];
			echo '" /><br>
			<textarea type="text" name="message" placeholder="MESSAGE (100 caractères max)" ></textarea><br>
			<input type="submit" value="Envoyer" />
		</form>';

	/*--- Ajoute dans la table chat de la base de données chrono_ng ---*/
	$_recupMsg = "";
	if (!empty($_POST['pseudo']) && !empty($_POST['message']) && strlen($_POST['message']) <= 100) {
			$_pseudo = $_POST['pseudo'];
			$_message = $_POST['message'];
			for ($i=0; $i < strlen($_message); $i++) { // Pour le probléme d'accent
				if ($_message[$i] != "'") {
					$_recupMsg = $_recupMsg.$_message[$i];
				}
				else
				{
					$_recupMsg = $_recupMsg.'\\'.$_message[$i];
				}
			}
			$bdd->exec("INSERT INTO chat(pseudo,message,heure_sms,date_sms) VALUES ('$_pseudo','$_recupMsg','$_heure','$_date')");
		}
}
	  	  
?>