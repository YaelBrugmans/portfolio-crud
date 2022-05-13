<?php

// paramètre de connexion à la base de donnée
$database = 'mysql:host=localhost;dbname=portfolio';
// $database = 'https://phpmyadmin.bes-webdeveloper-seraing.be/index.php?db=brugmans';
// https://phpmyadmin.bes-webdeveloper-seraing.be/index.php?route=/database/structure&server=1&db=brugmans
// http://192.99.2.109/phpmyadmin
// $databse = 'mysql:host=phpmyadmin.bes-webdeveloper-seraing.be;dbname=brugmans';
// $databse = 'mysql:host=localhost;dbname=portfolio';
// localhost http://localhost/phpmyadmin/index.php?route=/database/sql&db=portfolio
// https://phpmyadmin.bes-webdeveloper-seraing.be/index.php?route=/sql&db=brugmans&table=portfolio_index&pos=0
$user = 'brugmans';
$passw = 'Korsane1\'';
//$passw = 'gV5&gt6BZK';
$user = 'root';
$passw = '';

// connection database
$db = new PDO($database, $user, $passw);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
