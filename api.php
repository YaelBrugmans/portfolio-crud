<?php

// base de donnée
include 'db.php';

// les autorisations nécessaires à la db
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Content-Type: application/json');

// la page d'où vient l'information
$page = null;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

// vérification de la méthode POST ou GET, sinon un message d'erreur zst envoyé
switch($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $inputJSON = file_get_contents('php://input');
        $contactForm = json_decode($inputJSON, true);

        // les paramètres du mail de contact envoyé par l'utilisateur
        $mail_expediteur = $contactForm['mail_expediteur'];
        $mail_destinataire = $contactForm['mail_destinataire'];
        $objet = $contactForm['objet'];
        $contact_message = $contactForm['contact_message'];
        $contact_date = date_format(new DateTime($contactForm['date']),'d-m-Y H:i:s');

        // envoie du mail à la db, en requête SQL
        $request = $db->prepare("INSERT INTO `contact`(`mail_expediteur`, `mail_destinataire`, `objet`, `contact_message`, `contact_date`) VALUES ('$mail_expediteur', '$mail_destinataire', '$objet', '$contact_message', '$contact_date')");
        $request->execute();
        break;

    case 'GET':
        // en fonction de la page, on cherche la table appropriée, sinon on renvoie une erreur et break
        if($page == 'accueil' || $page == '`presentation`' || $page == '`travaux`' || $page == '`services`' || $page == '`contact`'){
            $request = $db->prepare("SELECT * FROM `$page`");
        }
        else {
            echo 'Erreur de localisation de la page';
            break;
        }
        // execute la requete
        $request->execute();
        $data = $request->fetchAll();
        echo json_encode($data);
        break;

    default:
        // si aucune méthode n'est reconnue, un message d'erreur est envoyé
        echo 'Méthode GET ou POST non reconnue';
        break;
}