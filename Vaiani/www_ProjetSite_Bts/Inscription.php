<?php include('connectBDD.php'); ?>

<?php
/* ----------- | Formulaire | ----------- */
echo '<form class="formulaire" action="Formulaire_inscription.php" method="post">
				<p>Nom: <input type="text" name="nom" required autofocus" /></p>
				<p>Prénom: <input type="text" name="prenom" required /></p>
				<p>Date de naissance: <input type="date" max="2019-12-31" min="1900-01-01" name="date_Naissance"required /></p>
				<p>Sexe:
					<input type="radio" name="Sexe" value="F" checked="checked" /><label for="F">Féminin</label>
					<input type="radio" name="Sexe" value="M" /><label for="M">Masculin</label>
				</p>';
				$date = date('Y:m:d');
				$requete = "SELECT idURSE, nomURSE FROM course WHERE dateURSE > '$date' ORDER BY dateURSE LIMIT 0,5";
				$nomCourse = $bdd->query($requete);
				echo '<p>Course: 
				<select name="course">';
				while ($infoCourse = $nomCourse->fetch()) {
					echo '<option value='.$infoCourse['idURSE'].'>'.$infoCourse['nomURSE'].'</option>';
				}
				echo '</select>
				</p>
								
				<p class="btn_inscrire"><input type="submit" value="S\'inscrire" /></p>
</form>';

// Pour vérifié si l'enregistrement n'existe déja pas
$exist = "";
$c = 0;
if (!empty($_POST['nom']) && !empty($_POST['course'])){
	$nn = $_POST['nom'];
	$pn = $_POST['prenom'];
	$c = $_POST['course']; // info: idURSE
	
	$rep = $bdd->query("SELECT * FROM coureur WHERE nomREUR='$nn' && prenomREUR='$pn' && idURSE='$c'");
	while ($don = $rep->fetch()){
		$exist = $don['nomREUR']; }
}

// Si c'est bon Inscriptions du participant
if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['date_Naissance'])){
	
	if ($exist=="" && !empty($_POST['course'])){
		$n = $_POST['nom'];
		$p = $_POST['prenom'];
		$d = $_POST['date_Naissance'];
		$s = $_POST['Sexe'];
		
		/*---------------------- Catégorie -----------------------*/
		$cat = 0;
		$annee = $_POST['date_Naissance'][0].$_POST['date_Naissance'][1].$_POST['date_Naissance'][2].$_POST['date_Naissance'][3];
		
		if ($annee >= 2013)
				if ($s == 'M' || $s == 'F')
					$cat = 1;		// Baby Athlé

			if ($annee >= 2010 && $annee <= 2012)
				if ($s == 'M' || $s == 'F')
					$cat = 2;		// 	École d'Athlétisme 

			if ($annee >= 2008 && $annee <= 2009)
				if ($s == 'M' || $s == 'F')
					$cat = 3;		// Poussin

			if ($annee >= 2006 && $annee <= 2007)
				if ($s == 'M' || $s == 'F')
					$cat = 4;		// Benjamin

			if ($annee >= 2004 && $annee <= 2005)
				if ($s == 'M' || $s == 'F')
					$cat = 5;		// Minime

			if ($annee >= 2002 && $annee <= 2003)
				if ($s == 'M' || $s == 'F')
					$cat = 6;		// Cadet

			if ($annee >= 2000 && $annee <= 2001 && $s == 'M')
				$cat = 7;		// Junior Homme

			if ($annee >= 2000 && $annee <= 2001 && $s == 'F')
				$cat = 8;		// Junior Femme

			if ($annee >= 1997 && $annee <= 1999 && $s == 'M')
				$cat = 9;		// Espoir Homme

			if ($annee >= 1997 && $annee <= 1999 && $s == 'F')
				$cat = 10;		// Espoir Femme

			if ($annee >= 1980 && $annee <= 1996 && $s == 'M')
				$cat = 11;		// Senior Homme

			if ($annee >= 1980 && $annee <= 1996 && $s == 'F')
				$cat = 12;		// Senior Femme

			if ($annee >= 1970 && $annee <= 1979 && $s == 'M')
				$cat = 13;		// Master 1 Homme

			if ($annee >= 1970 && $annee <= 1979 && $s == 'F')
				$cat = 14;		// Master 1 Femme

			if ($annee >= 1960 && $annee <= 1969 && $s == 'M')
				$cat = 15;		// Master 2 Homme

			if ($annee >= 1960 && $annee <= 1969 && $s == 'F')
				$cat = 16;		// Master 2 Femme

			if ($annee >= 1950 && $annee <= 1959)
				if ($s == 'M' || $s == 'F')
					$cat = 17;		// 	Master 3
			
			if ($annee >= 1940 && $annee <= 1949)
				if ($s == 'M' || $s == 'F')
					$cat = 18;		// 	Master 4 

			if ($annee <= 1939)
				if ($s == 'M' || $s == 'F')
					$cat = 19;		// 	Master 5 
		
		
		$bdd->exec("INSERT INTO coureur(nomREUR, prenomREUR, date_naissance, sexeREUR, idURSE, idGORIE) VALUES('$n', '$p', '$d', '$s', '$c', '$cat')");
		echo '<script type="text/javascript">alert("Votre inscription a bien été pris en compte")</script></p>';
	}
	
	elseif(empty($_POST['course']))
		echo '<script type="text/javascript">alert("Aucune course prévu pour l\'instant !")</script></p>';
	
	else
		echo '<script type="text/javascript">alert("Vous êtes déja inscrit")</script></p>';
}

/* ----------- # Affichage des prochaines courses # ----------- */
/*****/ $i=0;
$requeteAgenda = "SELECT * FROM course WHERE dateURSE>'$date' ORDER BY dateURSE, h_depart LIMIT 0,3";
$recupereAgenda = $bdd->query($requeteAgenda);
while ($afficheAgenda = $recupereAgenda->fetch()) {
	if($i<1) {
		echo '<p class="agenda">'.str_repeat('&nbsp',2).'Les prochaines courses:</p>';
		$i++;
	}
	echo '<span style="color:RGB(250,100,0); font-size:1.2em">'.date('d/m/Y', strtotime($afficheAgenda['dateURSE'])).'</span>'.str_repeat('&nbsp',20).'<span style="font-size:1.2em; color:black">Distance:</span>'.$afficheAgenda['distanceURSE'].'<br>';
	echo '<span style="color:Blue; font-size:1.2em">'.$afficheAgenda['nomURSE'].'</span>'.str_repeat('&nbsp',16).'<span style="font-size:1.2em; color:black">Heure:</span>'.$afficheAgenda['h_depart'].'<br>';
	echo  '<span style="color:green; font-size:1.2em">'.$afficheAgenda['lieuURSE'].'</span><br><br>';
	echo '--------------------------------------------------------------------<br>';
}

?>