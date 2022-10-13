<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="Resultat.css" />
	<link rel="icon" type="image/x-icon" href="dossier/logo.JPG" />
	<title>Résultat - chrono_ng</title>
</head>

<body>
		<!-- Menu et Logo -->
	<h1>
			<a href="index.php"><img src="dossier/logo.JPG" class="logo" alt="Photo de drapeau Polynésien" width="115" height="115" title="Porinetia Natirara" /></a><br />

			<a class="menu" href="Presentation.html"><span>PRESENTATION</span></a>

			<a class="menu" href="Formulaire_inscription.php"><span>INSCRIPTION</span></a>

			<a href="Resultat.php"><span>RESULTAT</span></a>

			<a class="menu" href="Contact.html"><span>CONTACT</span></a>
			
			<a class="direct" href="DirectCourse.php"><span>DIRECT</span></a>
	</h1>

	<h3>
		<a class="menu" href="http://localhost/">Acceuil</a> <em>>></em> Résultat
	</h3>

		<!-- Réseau social -->
		<p class="reseauSocial">
			<a href="https://www.facebook.com/" target="_blank"><img src="dossier/icone-facebook.jpg" class="iconeReseauSocial" alt="Logo de Facebook" title="Facebook" width="30" height="30" /></a>

			<a href="https://www.youtube.com/" target="_blank"><img src="dossier/icone-youtube.JPG" class="iconeReseauSocial" alt="Logo de Youtube" title="Youtube" width="35" height="25" /></a>
			
			<a href="https://twitter.com/?lang=fr" target="_blank"><img src="dossier/icone-twitter.png" class="iconeReseauSocial" alt="Logo de Twitter" title="Twitter" width="30" height="30" /></a>
			
			<a href="https://www.instagram.com/?hl=fr" target="_blank"><img src="dossier/icone-instagram.JPG" class="iconeReseauSocial" alt="Logo d'Instagram" title="Instagram" width="30" height="30" /></a>
		</p>

		<?php include('AfficheResultat.php'); ?>


	<footer style="position: fixed; bottom:0" >© 2019 BTS SN-IR du Couffignal</footer>
</body>
</html>