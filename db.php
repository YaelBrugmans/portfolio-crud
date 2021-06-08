<?php

// paramètre de connexion à la base de donnée
$databse = 'mysql:host=localhost;dbname=script_server;charset=utf8';
// $databse = 'http://localhost/phpmyadmin/index.php?route=/database/structure&server=1&db=script_server';
$user = 'root';
$passw = '';

// connection database
$db = new PDO($databse, $user, $passw);
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
