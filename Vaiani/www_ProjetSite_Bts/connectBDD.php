<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=chrono_ng;charset=utf8', 'root', '');
	//$bdd = new PDO('mysql:host=mysql-chrono.alwaysdata.net;dbname=chrono_ng;charset=utf8', 'chrono', 'chrono');
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

?>