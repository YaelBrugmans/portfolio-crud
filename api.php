<?php
// les autorisations nécessaires à la db
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Content-Type: application/json');

// base de donnée
include 'db.php';



// la page d'où vient l'information
$page = null;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

// vérification de la méthode POST ou GET, sinon un message d'erreur est envoyé
switch($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $inputJSON = file_get_contents('php://input');
        $contactForm = json_decode($inputJSON, true);

        // les paramètres du mail de contact envoyé par l'utilisateur
        $email = $contactForm['email'];
        $mail_destinataire = 'yaelbrugmans1998@gmail.com';
        $object = $contactForm['object'];
        $message = $contactForm['message'];
        $date = date_format(new DateTime($contactForm['date']),'d-m-Y H:i:s');

        // envoie du mail à la db, en requête SQL
        $request = $db->prepare("INSERT INTO `contact`(`date`, `email`, `object`, `message`) VALUES (`date`, ` . $email . `, ` . $object`, ` . $message`)");
        $request->execute();
        break;

    case 'GET':
        // en fonction de la page, on cherche la table appropriée, sinon on renvoie une erreur et break
        if($page == 'index' || $page == 'presentation' || $page == 'works' || $page == 'services' || $page == 'contact'){
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

    // si aucune méthode n'est reconnue, un message d'erreur est envoyé
    default:
        echo 'Méthode GET ou POST non reconnue';
        break;
}