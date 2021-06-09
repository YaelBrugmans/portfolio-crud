<?php

// paramètre de connexion à la base de donnée
$databse = 'mysql:host=localhost;dbname=script_server;charset=utf8';
$user = 'root';
$passw = '';

// connection database
$db = new PDO($databse, $user, $passw);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>