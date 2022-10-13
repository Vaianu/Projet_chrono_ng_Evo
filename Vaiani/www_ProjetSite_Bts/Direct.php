<?php include('connectBDD.php'); ?>

<?php

date_default_timezone_set('Europe/Paris');
$_date = date('Y-m-d');
$_heure = date('H:i:s');
echo '<span style="color:blue; font-size:1.4em; margin-left:10%">'.$_heure.'</span>';

// Récupére la course ou l'heure de départ est inférieur à l'heure actuelle
$SelectCourse = $bdd->query("SELECT * FROM course WHERE dateURSE='$_date' && h_depart<='$_heure' ORDER BY h_depart DESC LIMIT 0,1");
$reponse = $SelectCourse->fetch();

/* ----- # Calcul de l'heure de fin de course # ------ */ 
$heure_d = isset($reponse['h_depart']) ? $reponse['h_depart'] : null; // Récupération de l'heure de départ
if(!empty($heure_d)) {
	$heure_fin = $heure_d[0].$heure_d[1]; // Récupération que de l'heure
	$heure_fin = $heure_fin + 6; // Rajout de 6 heures en plus à l'heure de départ pour avoir l'heure de fin
	$heure_fin = $heure_fin.':00:00';
	
	if (strlen($heure_fin) < 8) { // Si heure est égale à 9:00:00 alors rajoute un 0 => 09:00:00
		$heure_fin = '0'.$heure_fin;
	}
}

if (!empty($reponse['nomURSE']) && $_heure < $heure_fin){ // Si l'heure actuelle est inférieur à l'heure de fin de course, alors c'est bon
	
	/* ---------- | Tableaux des résultats | ----------- */
	echo '<p class="infoCourse">Course: <strong style="color:blue">'.$reponse['nomURSE'].'</strong>'.str_repeat('&nbsp', 5).'Lieu: <strong style="color:green">'.$reponse['lieuURSE'].'</strong>'.str_repeat('&nbsp', 5).'Distance: <strong style="color:red">'.$reponse['distanceURSE'].'</strong><p>';
	$idURSE = $reponse['idURSE'];
	
		echo '<table>
  
			<thead>
				<tr>
					<th>Classement</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Dossard</th>
					<th>Temps</th>
					<th>Différence</th>
					<th>Situation</th>
				</tr>
			</thead>';

	$classementC = 1;
	
	/*-- Requête pour ce qui ont terminée la course --*/
	$requeteArrivee = $bdd->query("SELECT coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, chrono.difference, chrono.tempsFinal FROM (((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idREUR=coureur.idREUR && dossard.idURSE=course.idURSE) WHERE course.idURSE='$idURSE' && chrono.t_arrivee!='00:00:00' ORDER BY chrono.tempsFinal");
	$i = 0;
	while ($recupDonnees = $requeteArrivee->fetch()) {
		echo
			'<tr style="text-align: center;">
				<td>'.$classementC.'</td>
				<td>'.htmlspecialchars($recupDonnees['nomREUR']).'</td>
				<td>'.htmlspecialchars($recupDonnees['prenomREUR']).'</td>
				<td>'.$recupDonnees['numSARD'].'</td>
				<td>'.$recupDonnees['tempsFinal'].'</td>
				<td>'; if ($i<1) {echo '--'; $i++;}
					   else { 
							if ($recupDonnees['difference'][0] == 0 && $recupDonnees['difference'][1] == 0 && $recupDonnees['difference'][3] == 0 && $recupDonnees['difference'][4] == 0)
								echo '+'.$recupDonnees['difference'][6].$recupDonnees['difference'][7].'s';
							else if ($recupDonnees['difference'][0] == 0 && $recupDonnees['difference'][1] == 0)
								echo '+'.$recupDonnees['difference'][3].$recupDonnees['difference'][4].'min'.$recupDonnees['difference'][6].$recupDonnees['difference'][7].'s';
							else echo '+'.$recupDonnees['difference'][0].$recupDonnees['difference'][1].'h'.$recupDonnees['difference'][3].$recupDonnees['difference'][4].'min'.$recupDonnees['difference'][6].$recupDonnees['difference'][7].'s';
					   } echo '</td>
				<td style="color:red">Arrivée</td>
			</tr>';
	$classementC += 1;
	}
	
	/*-- Requête pour ce qui n'ont pas encore terminée la course --*/
	$requetInter = $bdd->query("SELECT coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, chrono.difference, chrono.tempsInter FROM (((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idREUR=coureur.idREUR && dossard.idURSE=course.idURSE) WHERE course.idURSE='$idURSE' && chrono.t_inter!='00:00:00' && chrono.t_arrivee='00:00:00' ORDER BY chrono.tempsInter");
	
	while ($recupDonnees = $requetInter->fetch()) {
		echo
 			'<tr style="text-align: center;">
				<td>'.$classementC.'</td>
				<td>'.htmlspecialchars($recupDonnees['nomREUR']).'</td>
				<td>'.htmlspecialchars($recupDonnees['prenomREUR']).'</td>
				<td>'.$recupDonnees['numSARD'].'</td>
				<td>'.$recupDonnees['tempsInter'].'</td>
				<td>'; if($i<1) { echo '--'; $i++;}
					   else {
							if ($recupDonnees['difference'][0] == 0 && $recupDonnees['difference'][1] == 0 && $recupDonnees['difference'][3] == 0 && $recupDonnees['difference'][4] == 0)
								echo '+'.$recupDonnees['difference'][6].$recupDonnees['difference'][7].'s';
							else if ($recupDonnees['difference'][0] == 0 && $recupDonnees['difference'][1] == 0)
								echo '+'.$recupDonnees['difference'][3].$recupDonnees['difference'][4].'min'.$recupDonnees['difference'][6].$recupDonnees['difference'][7].'s';
							else echo '+'.$recupDonnees['difference'][0].$recupDonnees['difference'][1].'h'.$recupDonnees['difference'][3].$recupDonnees['difference'][4].'min'.$recupDonnees['difference'][6].$recupDonnees['difference'][7].'s';
					   } echo '</td>
				<td style="color:green">Mi-parcours</td>
			</tr>';
	$classementC += 1;
	}

	////////// Affichage des messages pour le chat //////////
	$requeteMessage = $bdd->query("SELECT pseudo, message, heure_sms FROM chat WHERE date_sms='$_date' order by idCHAT DESC LIMIT 0,5");
	$nbr_msg = 0;
	$message = $requeteMessage->fetch();

	echo '<div class="encadrerDiscussion">';
	if (!empty($message['pseudo'])) {
		$liste_heurePost[] = $message['heure_sms'];
		$liste_pseudo[] = $message['pseudo'];
		$liste_msg[] = $message['message'];
		$nbr_msg++;
		while ($message = $requeteMessage->fetch()) {
			$liste_heurePost[] = $message['heure_sms'];
			$liste_pseudo[] = $message['pseudo'];
			$liste_msg[] = $message['message'];
			$nbr_msg++;
		}
		$indice_msg = $nbr_msg;
		$indice_msg--;
		echo '<div class="arriere_plan_chat"><span class="heurePosteMessage">['.$liste_heurePost[$indice_msg].']</span>@<span class="pseudo">'.htmlspecialchars($liste_pseudo[$indice_msg]).' :</span><span class="msg">'.htmlspecialchars($liste_msg[$indice_msg]).'</span></div>';

		if ($nbr_msg > 1) {
			for ($i = 0; $i < $nbr_msg-1; $i++)
			{
				$indice_msg--;
				echo '<div class="arriere_plan_chat"><span class="heurePosteMessage">['.$liste_heurePost[$indice_msg].']</span>@<span class="pseudo">'.htmlspecialchars($liste_pseudo[$indice_msg]).' :</span><span class="msg">'.htmlspecialchars($liste_msg[$indice_msg]).'</span></div>';
			}
		}
	}
	echo '</div>';
}
else {
	echo str_repeat('<br>',5)."<p style='text-align: center; font-size: 4em; background: yellow'>Je cours vers le but<p>";
	
	// Affiche les informations de la course d'aujourd'hui qui commence bientôt
	$requeteURSE = $bdd->query("SELECT * FROM course WHERE dateURSE = '$_date' && h_depart >= '$_heure' ORDER BY dateURSE, h_depart LIMIT 0,1");
	$donneesURSE = $requeteURSE->fetch();
	if (isset($donneesURSE['idURSE'])) {
		echo '<div style="text-align: center;"><span style="font-size: 1.8em; color: rgb(255,128,0)">Prochaine course:<br></span> ';
	
		echo $donneesURSE['nomURSE'].str_repeat('&nbsp',5).$donneesURSE['lieuURSE'].str_repeat('&nbsp',5).$donneesURSE['distanceURSE'].str_repeat('&nbsp',5).date('d/m/Y', strtotime($donneesURSE['dateURSE'])).str_repeat('&nbsp',3).'à  '.$donneesURSE['h_depart'].'</div><br>';
	}
}

?>