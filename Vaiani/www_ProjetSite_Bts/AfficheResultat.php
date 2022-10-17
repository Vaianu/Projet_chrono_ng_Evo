<?php include('connectBDD.php'); ?> <!-- Connexion à la base de données -->

<?php
session_start(); // Pour que l'information du choix de la course soit accessible sur la page "pdf.php"
date_default_timezone_set('Europe/Paris');
$date = date('Y-m-d'); // Récupéreration de la date actuelle
$_heure = date('H:i:s'); // Heure actuelle format 10:00:00

// Récupére la course ou l'heure de départ est inférieur à l'heure actuelle
$SelectCourse = $bdd->query("SELECT * FROM course WHERE dateURSE='$date' && h_depart<='$_heure' ORDER BY h_depart DESC LIMIT 0,1");
$reponse = $SelectCourse->fetch();

/* ------------- # Calcul de l'heure de fin de course # -------------- */
$heure_d = $reponse ? $reponse['h_depart'] : null;
//echo $heure_d.' Heure départ<br>';
global $heure_fin;
if(!empty($heure_d)) {
	$heure_fin = $heure_d[0].$heure_d[1]; // Récupére l'heure
	$heure_fin = $heure_fin + 6; // Rajout de 6 heure en plus à l'heure de départ pour avoir l'heure de fin
	$heure_fin = $heure_fin.':00:00';
	if (strlen($heure_fin) < 8) // Si heure est égale à 9:00:00 alors rajoute un 0 => 09:00:00
		$heure_fin = '0'.$heure_fin;
	
	//echo $heure_fin.' Heure fin<br>';
	//echo $_heure.' Heure courante<br>';
}

global $SelectnomCourse;
/* -- Total participant Depart -- */
$idURSE = $reponse ? $reponse['idURSE'] : null;
$requeteTotalDepart = $bdd->query("SELECT COUNT(*) AS totalD FROM chrono WHERE idURSE='$idURSE'");
$totalParticipantDepart = $requeteTotalDepart->fetch();
$nbCoureurDepart = $totalParticipantDepart['totalD'];
if ($nbCoureurDepart == 0)
	$nbCoureurDepart = 1; // Pour éviter que la course d'aujourd'hui s'affiche dans la page résultat si $nbCoureurDepart et $nbCoureurArrivee est égale à 0

/* -- Total participant Arrivee -- */	
$requeteTotalArrivee = $bdd->query("SELECT COUNT(*) AS totalA FROM chrono WHERE idURSE='$idURSE' && t_arrivee!='00:00:00'");
$totalParticipantArrivee = $requeteTotalArrivee->fetch();
$nbCoureurArrivee = $totalParticipantArrivee['totalA'];
if(empty($heure_fin)) // Pour évité que $_heure soit supérieur à $heure_fin s'il n'y pas de course aujourd'hui
	$heure_fin = '23:59:59';
	
if ($reponse ? $reponse['dateURSE'] : null == $date && $nbCoureurDepart == $nbCoureurArrivee || $_heure > $heure_fin ) // Si l'heure courante est supérieur à l'heure de fin de course
{
	$SelectnomCourse = $bdd->query("SELECT idURSE, nomURSE FROM course WHERE dateURSE <= '$date' ORDER BY dateURSE DESC"); // Récupére les courses fini si le direct est fini
}
else
{
	$SelectnomCourse = $bdd->query("SELECT idURSE, nomURSE FROM course WHERE dateURSE < '$date' ORDER BY dateURSE DESC"); // Récupére les courses fini avant hier
}


/* -------@ Méthode POST @-------- */
echo '<p><form method="post" action="Resultat.php">
		<select name="choixCourse" style="margin-left: 40%">';
		
while ($nomCourse = $SelectnomCourse->fetch()) {
    echo '<option value="'.$nomCourse['idURSE'].'">'.$nomCourse['nomURSE'].'</option>';
}
echo 	'</select>

		<select name="categorie">
				<option value="0">Général</option>
			<optgroup label="Par catégorie">
				<option value="1">Baby Athlé</option>
				<option value="2">École d\'Athlétisme</option>
				<option value="3">Poussin</option>
				<option value="4">Benjamin</option>
				<option value="5">Minime</option>
				<option value="6">Cadet</option>
				<option value="7">Junior Homme</option>
				<option value="8">Junior Femme</option>
				<option value="9">Espoir Homme</option>
				<option value="10">Espoir Femme</option>
				<option value="11">Senior Homme</option>
				<option value="12">Senior Femme</option>
				<option value="13">Master 1 Homme</option>
				<option value="14">Master 1 Femme</option>
				<option value="15">Master 2 Homme</option>
				<option value="16">Master 2 Femme</option>
				<option value="17">Master 3</option>
				<option value="18">Master 4</option>
				<option value="19">Master 5</option>
			</optgroup>
		</select>
		<input type="submit" value="Consulter" />
	</form><p>';
	
// Variable globale: Pour éviter de récupérer deux fois de suite les informations de la course
global $nomC;
global $lieuC;
global $distanC;
global $dateC;

global $choixCourse;
/* --------" Information concernant la course choisi "------- */
if (isset($_POST['choixCourse'])) {
	$choixCourse = $_POST['choixCourse']; // On récupére le choix de la course choisi par l'internaute
	
	$requeteInfoURSE = $bdd->query("SELECT * FROM course WHERE idURSE='$choixCourse'");
	$donneesCourse = $requeteInfoURSE->fetch();
	$nomC = $donneesCourse['nomURSE'];
	$lieuC = $donneesCourse['lieuURSE'];
	$distanC = $donneesCourse['distanceURSE'];
	$dateC = $donneesCourse['dateURSE'];
}


/* ---------- " Calcul des temps / Affichage en brut " ---------- */
if (isset($_POST['choixCourse']) && ($_POST['categorie'])=='0') {
	/* ---------- | Affichage en brut | ----------- */
echo '<div class="conteneur">
	<table style="width: 60%">
			<caption>Course: <strong style="color:blue">'.$nomC.'</strong>'.str_repeat('&nbsp', 5).'Lieu: <strong style="color:green">'.$lieuC.'</strong>'.str_repeat('&nbsp', 5).'Distance: <strong style="color:red">'.$distanC.'</strong>'.str_repeat('&nbsp', 5).'Date: <strong style="color:black">'.date('d/m/Y', strtotime($dateC)).'</strong></caption>
		
		<thead>
			<tr>
				<th>Classement</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Dossard</th>
				<th>Temps</th> 
			</tr>
		</thead>';

$classementG = 1;
	
	/* ------- | Requête pour ce qui ont terminée la course | ------- */
	$requeteClassement = $bdd->query("SELECT coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, chrono.tempsFinal FROM (((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idREUR=coureur.idREUR && dossard.idURSE=course.idURSE) WHERE course.idURSE='$choixCourse' && chrono.t_arrivee!='00:00:00' ORDER BY chrono.tempsFinal");
	
	while ($recupDonnees = $requeteClassement->fetch()) {
		echo
 			'<tr style="text-align: center;">
				<td>'.$classementG.'</td>
				<td>'.htmlspecialchars($recupDonnees['nomREUR']).'</td>
				<td>'.htmlspecialchars($recupDonnees['prenomREUR']).'</td>
				<td>'.$recupDonnees['numSARD'].'</td>
				<td>'.$recupDonnees['tempsFinal'].'</td>
			</tr>';
	$classementG += 1;
	}
	
	/* ------ | Requête pour ce qui n'ont pas terminée la course | ------ */
	$requeteClassement = $bdd->query("SELECT coureur.nomREUR, coureur.prenomREUR, dossard.numSARD FROM (((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idURSE=course.idURSE && dossard.idREUR=coureur.idREUR) WHERE course.idURSE='$choixCourse' && chrono.t_arrivee='00:00:00'");
	
	while ($recupDonnees = $requeteClassement->fetch()) {
		echo
 			'<tr style="text-align: center;">
				<td>'.$classementG.'</td>
				<td>'.htmlspecialchars($recupDonnees['nomREUR']).'</td>
				<td>'.htmlspecialchars($recupDonnees['prenomREUR']).'</td>
				<td>'.$recupDonnees['numSARD'].'</td>
				<td>Abandon</td>
			</tr>';
	$classementG += 1;
	}
echo '</table>';

	/* ----------------- ## PDF ## ------------------- */
$_SESSION['choixCourse'] = $choixCourse; // Choix course
echo '<a class="pdf" href="fpdf181/pdf.php" target="_blank">Résultat en PDF</a>'; // Rediriger vers "pdf.php"
}


	/* ---------- | Affichage par catégorie | --------- */
else if (isset($_POST['choixCourse'])) {	
	/* ------------ | Affichage par catégorie | -------------- */
	echo '<div class="conteneur">
	<table style="width: 60%">
			<caption>Course: <strong style="color:blue">'.$nomC.'</strong>'.str_repeat('&nbsp', 5).'Lieu: <strong style="color:green">'.$lieuC.'</strong>'.str_repeat('&nbsp', 5).'Distance: <strong style="color:red">'.$distanC.'</strong>'.str_repeat('&nbsp', 5).'Date: <strong style="color:black">'.date('d/m/Y', strtotime($dateC)).'</strong></caption>

		<thead>
			<tr>
				<th>Classement</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Dossard</th>
				<th>Catégorie</th>
				<th>Temps</th> 
			</tr>
		</thead>';
	
	$classementC = 1;
	$categorie = $_POST['categorie']; // choix catégorie

	$requeteGorie = $bdd->query("SELECT coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, categorie.nomGORIE, categorie.sexeGORIE, chrono.tempsFinal FROM ((((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idREUR=coureur.idREUR && dossard.idURSE=course.idURSE) INNER JOIN categorie ON categorie.idGORIE=coureur.idGORIE) WHERE course.idURSE='$choixCourse' && coureur.idGORIE='$categorie' && chrono.t_arrivee!='00:00:00' ORDER BY chrono.tempsFinal");
	
	while ($recupDonnees = $requeteGorie->fetch()) {
		echo
 			'<tr style="text-align: center;">
				<td>'.$classementC.'</td>
				<td>'.htmlspecialchars($recupDonnees['nomREUR']).'</td>
				<td>'.htmlspecialchars($recupDonnees['prenomREUR']).'</td>
				<td>'.$recupDonnees['numSARD'].'</td>
				<td>'.$recupDonnees['nomGORIE'].' '.$recupDonnees['sexeGORIE'].'</td>
				<td>'.$recupDonnees['tempsFinal'].'</td>
			</tr>';
		$classementC += 1;
	}
	
	/*------# Affichage pour les abandons #------*/
	$requeteGorie = $bdd->query("SELECT coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, categorie.nomGORIE, categorie.sexeGORIE FROM ((((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idREUR=coureur.idREUR && dossard.idURSE=course.idURSE) INNER JOIN categorie ON categorie.idGORIE=coureur.idGORIE) WHERE course.idURSE='$choixCourse' && coureur.idGORIE='$categorie' && chrono.t_arrivee='00:00:00'");
	
	while ($recupDonnees = $requeteGorie->fetch()) {
		echo
 			'<tr style="text-align: center;">
				<td>'.$classementC.'</td>
				<td>'.htmlspecialchars($recupDonnees['nomREUR']).'</td>
				<td>'.htmlspecialchars($recupDonnees['prenomREUR']).'</td>
				<td>'.$recupDonnees['numSARD'].'</td>
				<td>'.$recupDonnees['nomGORIE'].' '.$recupDonnees['sexeGORIE'].'</td>
				<td>Abandon</td>
			</tr>';
		$classementC += 1;
	}
	echo '</table>';
}

?>
	