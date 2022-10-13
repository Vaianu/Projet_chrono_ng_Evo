
<?php include('CalculTemps.php'); ?>

<?php include('connectBDD.php'); ?>

<?php

$_date = date('Y-m-d');
$_heure = date('H:i:s');
echo '<span style="color:blue; font-size:1.4em; margin-left:10%">'.$_heure.'</span>';

// Récupére la course ou l'heure de départ est inférieur à l'heure actuelle
$SelectCourse = $bdd->query("SELECT * FROM course WHERE dateURSE='$_date' && h_depart<='$_heure' ORDER BY h_depart DESC LIMIT 0,1");
$reponse = $SelectCourse->fetch();

/* ----- # Calcul de l'heure de fin de course # ------ */ 
$heure_d = $reponse['h_depart']; // Récupération de l'heure de départ
if(!empty($heure_d)) {
	$heure_fin = $heure_d[0].$heure_d[1]; // Récupération que de l'heure
	$heure_fin = $heure_fin + 6; // Rajout de 6 heures en plus à l'heure de départ pour avoir l'heure de fin
	$heure_fin = $heure_fin.':00:00';
	
	if (strlen($heure_fin) < 8) { // Si heure est égale à 9:00:00 alors rajoute un 0 => 09:00:00
		$heure_fin = '0'.$heure_fin;
	}
}

if (!empty($reponse['nomURSE']) && $_heure < $heure_fin){ // Si l'heure actuelle est inférieur à l'heure de fin de course, alors c'est bon
	//echo '<br>'.$heure_d.' Heure de départ<br>';
	//echo $heure_fin.' Heure de fin';
	
	$idURSE = $reponse['idURSE'];
	/* --------------- @  Calculs du temps mis par le coureur entre la station de départ et la station intermédiaire  @ ----------------- */
	$reponseInter = $bdd->query("SELECT coureur.idREUR, chrono.t_depart, chrono.t_inter FROM ((chrono INNER JOIN course ON chrono.idURSE=course.idURSE)INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) WHERE course.idURSE='$idURSE' && chrono.t_inter!='00:00:00' && chrono.t_arrivee='00:00:00' && chrono.tempsInter='00:00:00' ORDER BY chrono.t_inter");
	
	while ($donnees = $reponseInter->fetch()) {

		$heure_depart = $donnees['t_depart'][0].$donnees['t_depart'][1];
		$min_depart = $donnees['t_depart'][3].$donnees['t_depart'][4];
		$seconde_depart = $donnees['t_depart'][6].$donnees['t_depart'][7];
	
		$heure_inter = $donnees['t_inter'][0].$donnees['t_inter'][1]; 
		$min_inter = $donnees['t_inter'][3].$donnees['t_inter'][4];
		$seconde_inter = $donnees['t_inter'][6].$donnees['t_inter'][7];

		$tempsDepart = new Temps($heure_depart, $min_depart, $seconde_depart);
		$tempsInter = new Temps($heure_inter, $min_inter, $seconde_inter);
		$tempsTotal = new Temps();
		$tempsTotal->calculTemps($tempsInter, $tempsDepart);
	
		/* ------- #  Mis à jour dans la table "chrono" pour le "temps intermédiaire" mis entre la station de départ et la station intermédiaire  # ------- */
		$recupTempsBdd = $tempsTotal->getTempsBdd();
		$idREUR = $donnees['idREUR'];
		$bdd->exec("UPDATE chrono SET tempsInter='$recupTempsBdd' WHERE idREUR='$idREUR'");
	}
	
	/*----# Pour Anu #----*/
		/* --------------- @  Calculs du temps mis par le coureur entre la station intermédiaire et la station arrivée  @ ----------------- */
	$reponseInter = $bdd->query("SELECT coureur.idREUR, chrono.t_inter, chrono.t_arrivee FROM ((chrono INNER JOIN course ON chrono.idURSE=course.idURSE)INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) WHERE course.idURSE='$idURSE' && chrono.t_arrivee!='00:00:00' && chrono.tempsArrivee='00:00:00' ORDER BY chrono.t_Arrivee");
	
	while ($donnees = $reponseInter->fetch()) {

		$heure_intermediaire = $donnees['t_inter'][0].$donnees['t_inter'][1];
		$min_intermediaire = $donnees['t_inter'][3].$donnees['t_inter'][4];
		$seconde_intermediaire = $donnees['t_inter'][6].$donnees['t_inter'][7];
	
		$heure_arrivee = $donnees['t_arrivee'][0].$donnees['t_arrivee'][1]; 
		$min_arrivee = $donnees['t_arrivee'][3].$donnees['t_arrivee'][4];
		$seconde_arrivee = $donnees['t_arrivee'][6].$donnees['t_arrivee'][7];

		$tempsIntermediaire = new Temps($heure_intermediaire, $min_intermediaire, $seconde_intermediaire);
		$tempsArrivee = new Temps($heure_arrivee, $min_arrivee, $seconde_arrivee);
		$tempsTotal = new Temps();
		$tempsTotal->calculTemps($tempsArrivee, $tempsIntermediaire);
	
		/* ------- #  Mis à jour dans la table "chrono" pour le "temps intermédiaire" mis entre la station de départ et la station intermédiaire  # ------- */
		$recupTempsBdd = $tempsTotal->getTempsBdd();
		$idREUR = $donnees['idREUR'];
		$bdd->exec("UPDATE chrono SET tempsArrivee='$recupTempsBdd' WHERE idREUR='$idREUR'");
	}
	
	
	
	
	/* --------------- @  Calculs du temps mis par le coureur entre la station de départ et la station arrivée  @ ------------------ */
	
	$reponseArrivee = $bdd->query("SELECT coureur.idREUR, chrono.t_depart, chrono.t_arrivee FROM ((chrono INNER JOIN course ON chrono.idURSE=course.idURSE)INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) WHERE course.idURSE='$idURSE' && chrono.t_arrivee!='00:00:00' && chrono.tempsFinal='00:00:00' ORDER BY chrono.t_arrivee");
	
	while ($donneesA = $reponseArrivee->fetch()) {

		$heure_depart = $donneesA['t_depart'][0].$donneesA['t_depart'][1];
		$min_depart = $donneesA['t_depart'][3].$donneesA['t_depart'][4];
		$seconde_depart = $donneesA['t_depart'][6].$donneesA['t_depart'][7];
	
		$heure_arrivee = $donneesA['t_arrivee'][0].$donneesA['t_arrivee'][1]; 
		$min_arrivee = $donneesA['t_arrivee'][3].$donneesA['t_arrivee'][4];
		$seconde_arrivee = $donneesA['t_arrivee'][6].$donneesA['t_arrivee'][7];

		$tempsDepart = new Temps($heure_depart, $min_depart, $seconde_depart);
		$tempsArrivee = new Temps($heure_arrivee, $min_arrivee, $seconde_arrivee);
		$tempsTotal = new Temps();
		$tempsTotal->calculTemps($tempsArrivee, $tempsDepart);
	
		/* ----- #  Mis à jour dans la table "chrono" pour le "tempsFinal" mis entre la station départ et la station arrivée  # ------ */
		$recupTempsBdd = $tempsTotal->getTempsBdd();
		$idREUR = $donneesA['idREUR'];
		$bdd->exec("UPDATE chrono SET tempsFinal='$recupTempsBdd' WHERE idREUR='$idREUR'");
	}
	
	/* ----- "  Calcul de l'écart entre le premier et les suivants pour la station arrivée " ------ */
	
	$tempsFinal = $bdd->query("SELECT tempsFinal, idREUR FROM chrono WHERE idURSE='$idURSE' && tempsFinal!='00:00:00' ORDER BY tempsFinal");
	
	$tempsPremier = $tempsFinal->fetch();
	$heure_Premier = $tempsPremier['tempsFinal'][0].$tempsPremier['tempsFinal'][1];
	$minute_Premier = $tempsPremier['tempsFinal'][3].$tempsPremier['tempsFinal'][4];
	$seconde_Premier = $tempsPremier['tempsFinal'][6].$tempsPremier['tempsFinal'][7];
	
	while ($difference = $tempsFinal->fetch()) {
	
		$heure_Suivant = $difference['tempsFinal'][0].$difference['tempsFinal'][1]; 
		$min_Suivant = $difference['tempsFinal'][3].$difference['tempsFinal'][4];
		$seconde_Suivant = $difference['tempsFinal'][6].$difference['tempsFinal'][7];
	
		$chronoPremier = new Temps($heure_Premier, $minute_Premier, $seconde_Premier);
		$chronoSuivant = new Temps($heure_Suivant, $min_Suivant, $seconde_Suivant);
		$chronoTotal = new Temps();
		$chronoTotal->calculTemps($chronoSuivant, $chronoPremier);
	
		/* ----- #  Mis à jour dans la table "chrono" pour la "difference" entre le premier et les suivants  # ------ */
		$recupChronoBdd = $chronoTotal->getTempsBdd();
		$idREUR = $difference['idREUR'];
		$bdd->exec("UPDATE chrono SET difference='$recupChronoBdd' WHERE idREUR='$idREUR'");
	}

	/* ----- "  Calcul de l'écart entre le premier et les suivants pour la station intermédiaire " ------ */
	
	$tempsInterPremier = $bdd->query("SELECT tempsInter FROM chrono WHERE idURSE='$idURSE' && tempsInter!='00:00:00' ORDER BY tempsInter LIMIT 0,1");
	$tempsChampion = $tempsInterPremier->fetch();
	$heure_Champion = $tempsChampion['tempsInter'][0].$tempsChampion['tempsInter'][1];
	$minute_Champion = $tempsChampion['tempsInter'][3].$tempsChampion['tempsInter'][4];
	$seconde_Champion = $tempsChampion['tempsInter'][6].$tempsChampion['tempsInter'][7];
	
	$tempsInter = $bdd->query("SELECT tempsInter, idREUR FROM chrono WHERE idURSE='$idURSE' && tempsInter!='00:00:00' && tempsFinal='00:00:00' ORDER BY tempsInter");
	
	while ($difference = $tempsInter->fetch()) {
	
		$heure_Suivant = $difference['tempsInter'][0].$difference['tempsInter'][1]; 
		$min_Suivant = $difference['tempsInter'][3].$difference['tempsInter'][4];
		$seconde_Suivant = $difference['tempsInter'][6].$difference['tempsInter'][7];
	
		$chronoChampion = new Temps($heure_Champion, $minute_Champion, $seconde_Champion);
		$chronoSuivant = new Temps($heure_Suivant, $min_Suivant, $seconde_Suivant);
		$chronoTotal = new Temps();
		$chronoTotal->calculTemps($chronoSuivant, $chronoChampion);
	
		/* ----- #  Mis à jour dans la table "chrono" pour la "difference" entre le premier et les suivants  # ------ */
		$recupChronoBdd = $chronoTotal->getTempsBdd();
		$idREUR = $difference['idREUR'];
		$bdd->exec("UPDATE chrono SET difference='$recupChronoBdd' WHERE idREUR='$idREUR'");
	}
			

	/* ---------- | Tableaux des résultats | ----------- */
	echo '<p class="infoCourse">Course: <strong style="color:blue">'.$reponse['nomURSE'].'</strong>'.str_repeat('&nbsp', 5).'Lieu: <strong style="color:green">'.$reponse['lieuURSE'].'</strong>'.str_repeat('&nbsp', 5).'Distance: <strong style="color:red">'.$reponse['distanceURSE'].'</strong><p>';
	$idU = $reponse['idURSE'];
	
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
	if ($donneesURSE['idURSE'] != null) {
		echo '<div style="text-align: center;"><span style="font-size: 1.8em; color: rgb(255,128,0)">Prochaine course:<br></span> ';
	
		echo $donneesURSE['nomURSE'].str_repeat('&nbsp',5).$donneesURSE['lieuURSE'].str_repeat('&nbsp',5).$donneesURSE['distanceURSE'].str_repeat('&nbsp',5).date('d/m/Y', strtotime($donneesURSE['dateURSE'])).str_repeat('&nbsp',3).'à  '.$donneesURSE['h_depart'].'</div><br>';
	}
}

?>