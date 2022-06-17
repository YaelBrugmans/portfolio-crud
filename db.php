<?php

// paramètres de la base de donnée
$user = 'brugmans';
$passw = 'Korsane1';
$user = 'root';
$passw = '';
$port = '22';
$dbName = 'brugmans';
$host = 'https://phpmyadmin.bes-webdeveloper-seraing.be';
$host = 'localhost';
$database = 'mysql:dbname=' . $dbName . ';host=' . $host . ';charset=utf8';
$database = 'mysql:dbname=portfolio;host=localhost;charset=utf8';

// connection database
try{
    $db = new PDO($database, $user, $passw);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    printf("Échec de la connexion : %s\n", $e->getMessage());
    exit();
}
