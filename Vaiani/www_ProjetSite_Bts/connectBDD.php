<?php

$conf = include('config.php');
try
{
	$bdd = new PDO('mysql:host='.$conf['host'].';dbname='.$conf['dbname'].';charset=utf8', $conf['user'], $conf['password']);
	//$bdd = new PDO('mysql:host=mysql-chrono.alwaysdata.net;dbname=chrono_ng;charset=utf8', 'chrono', 'chrono');
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

?>