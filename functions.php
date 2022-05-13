<?php

// les diverses fonctions nécessaires à chaque page pour son fonctionnement
include 'login.php';
include 'accueil.php';
include 'presentation.php';
include 'works.php';
include 'services.php';
include 'contact.php';
//include 'serviceManager.php';
$prefix = 'portfolio_';

// le head de la page html
function getHead() {
    $head = '
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crud Yael Brugmans Portfolio 2021</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <!--  load stylesheet style.css  -->
        <link rel="stylesheet" href="style.css">
        <!--  load font Roboto  -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> 
        <link rel="shortcut icon" href="#">
        
        <link rel="stylesheet" href="style.css">
    </head>';

    return $head;
}

// navigation par défaut de chaque page, vers chaque page
function getNav() {
    //global $_SESSION['serviceMessage'];
    $target = ['index' => 1, 'presentation' => 1, 'works' => 1, 'services' => 1, 'contact' => 1];
    //$target['index'] == true; $target['presentation'] == true; $target['works'] == true; $target['services'] == true; $target['contact'] = true;
    $nav = '
    <header>
        <nav>
            <ul class="nav first-nav">
                <!--   links to pages Accueil.html, presentation.html, works.html, services.html, contact.html        -->
                <li class="nav-item">
                    <a class="nav-link ' . ($target['index'] ? 'target' : '') . '" href="?page=index&table=index&action=list">Accueil</a>
                <!--    <a class="nav-link ' . ($target['index'] ? 'target' : '') . '" href="?page=index&action=list">Accueil</a>
                   -->
                </li>
                <li class="nav-item">
                    <a class="nav-link ' . ($target['presentation'] ? 'target' : '') . '" href="?page=presentation&table=presentation&action=list">Présentation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ' . ($target['works'] ? 'target' : '') . '" href="?page=works&table=works&action=list">Travaux Réalisés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ' . ($target['services'] ? 'target' : '') . '" href="?page=services&table=services&action=list">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ' . ($target['contact'] ? 'target' : '') . '" href="?page=contact&table=contact&action=list">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Se déconnecter</a>
                </li>
            </ul>
        </nav>
    </header>';

    return $nav;
}

// le footer de chaque page
function getFooter() {
    $nav = '
    <footer style="margin-top: 60px;">
        <p>Yael-Brugmans-<a class="nav-link" href="mailto:yaelbrugmans1998@gmail.com">yaelbrugmans1998@gmail.com</a>
        </p>
    </footer>';

    return $nav;
}

// si un formulaire est posté, on vérifie qu'il a été posté correctement
// todo verifier tous les champs toutes les tables
function isFormSubmit() : bool {
    $valid = false;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $valid = true;
    }
    if (isset($_POST['id'])){
        $valid = true;
    }
    return $valid; // Si on a au moins une requête POST['REQUEST_METHOD'] c'est que normalement le formulaire a été posté.
}

// on vérifie si le formulaire posté correctement a été rempli correctement
function isFormValid() : bool {
    // les données ne doivent pas être vides
    $form =  
        (isset($_POST['id']));

    var_dump($form);
    var_dump($_FILES['image']);
    var_dump($_FILES['image']['contact']);
    $get = $_FILES;

    // si il y a un forme, qu'une image a bien été envoyé et que le fichier n'a strictement aucune erreur, 
    if ($form && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // si l'image est dans un autre format que jpg, jpeg et png, on retourne un message d'erreur
        $imageParameters = pathinfo($_FILES['image']['name']);
        $imageExtension = $imageParameters['extension'];
        $extensionsAllowed = array('jpg', 'jpeg', 'png');
        if (!in_array($imageExtension, $extensionsAllowed)) {
            writeServiceMessage("Seulement les extensions " . implode(", ", $extensionsAllowed) . " sont autorisées");
            $form = false;
        }
        // si la taille de l'image excède 1 000 000 de pixels, on retourne un message d'erreur
        if ($_FILES['image']['size'] > 1000000) {
            writeServiceMessage("L'image est trop grande");
            $form = false;
        }
    }
    else
        writeServiceMessage("Formulaire invalide");

    return $form; 
}

// écrit un message d'erreur sur la session de l'utilisateur, en cas de problème
function writeServiceMessage($message) {
    $_SESSION['serviceMessage'] = $message;
}

// un message d'erreur s'affiche sur l'écran de l'utilisateur, en cas de problème, sinon le message est null
function getServiceMessage() {
    $message = null;
    if (isset($_SESSION['serviceMessage'])) {
        $message = $_SESSION['serviceMessage'];
        unset($_SESSION['serviceMessage']);
    }
    return $message;
}

// si l'image envoyé par l'utilisateur est bien téléchargé par post, on lui donne un chemin d'accès et un identifiant unique
function uploadImage($db, $image) {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $max_file_size = 1024 * 400;
    $extension = strtlower(pathinfo($image['name'])['extension']);
    $filepath = null;
    global $prefix;
    if(in_array($extension, $allowed_extensions)){
        if ($image['size'] < $max_file_size){
            //move_uploaded_file($image['tmp_name'], __DIR__ . '/images/' . uniqid() . '-' . $image['name']);
            move_uploaded_file($image['tmp_name'], __DIR__ . '/images/' . $image['name']);
            $filepath = $image['name']['extension'];
            $request = $db->prepare('INSERT INTO ' . $prefix . 'image (`name`, `extansion`, `format`) VALUES (:name, :extansion, :format)');
            $params = [
                'name' => $image['name'],
                'extansion' => $image['extension'],
                'format' => $image['size'],
            ];
            if ($request->execute($params))
                writeServiceMessage('Créée avec succès!');
        } else
            echo 'La taille maximum est de ' . $max_file_size . ' par fichier.';
    } else
        echo 'Seul les fichiers images sont autorisés';

    var_dump($_FILES['image']);

    return $filepath;

    //$filePath = './images/' . basename($image['name']['extension']);
    //if(move_uploaded_file($image['tmp_name'], $filePath))
    //    return $filePath;
    //else
    //    return null;
}

// retourne la table demandée en fonction de la page, sinon on affiche un message d'erreur et la table index est sélectionnée par défaut
function displayTable($db, $table) {
    global $prefix;
    $request = $db->prepare('SELECT * FROM ' . $prefix . $table);
    $request->execute();
    $lines = $request->fetchAll();

    if($table === 'index')
        $content = getTableIndex($lines);
    else if($table === 'presentation')
        $content = getTablePresentation($lines);
    else if($table === 'works')
        $content = getTableWorks($lines);
    else if($table === 'services')
        $content = getTableServices($lines);
    else if($table === 'contact')
        $content = getTableContact($lines);
    else{
        writeServiceMessage('Une erreur est survenue, lors de la sélection de la page');
        $content = getTableIndex($lines);
    }

    return $content;
}

// on crée une nouvelle ligne dans un tableau
function createdataLine($db, $table, $action){
    global $prefix;
    // si un formulaire est envoyé, on peut continuer la création de la ligne, sinon on retourne le formulaire avec aucune donnée
    if(isFormSubmit()) {
        var_dump($prefix);
        // si le formulaire est valide, on peut continuer la création de la ligne, sinon on retourne le formulaire avec aucune donnée
        if (isFormValid()) {
            var_dump($action);
            var_dump($_POST);
            $filePath = null;
            $date = null;
            //var_dump($_POST['image']);
            if(isset($_POST['image'])) {
                var_dump($_POST['image']);
                $filePath = uploadImage($db, $_POST['image']);
                var_dump($filePath);
            }
            if(isset($_POST['date'])) {
                var_dump($_POST['date']);
                //$date = verifyDate($_POST['date']);
                $date = $_POST['date'];
                var_dump($date);
            }
            if(isset($_POST['dateTime'])) {
                var_dump($_POST['dateTime']);
                //$date = verifyDate($_POST['date']);
                $dateTime = $_POST['dateTime'];
                var_dump($date);
            }
            // on selectionne la table appropriée, et on prépare les données
            if($table == 'index'){
                $request = $db->prepare('INSERT INTO ' . $prefix . $table . ' (`title`, `response`, `image`) VALUES (:title, :response, :image)');
                $params = [
                    'title' => $_POST['title'],
                    'response' => $_POST['response'],
                    'image' => $filePath
                ];
            }
            else if($table == 'presentation'){
                $request = $db->prepare('INSERT INTO ' . $prefix . $table . ' (`title`, `description`) VALUES (:title, :description)');
                $params = [
                    'title' => $_POST['title'],
                    'description' => $_POST['texte']
                ];
            }
            else if($table == 'works'){
                $request = $db->prepare('INSERT INTO ' . $prefix . $table . ' (`title`, `description`, `img`, `date`, `link`) VALUES (:title, :description, :img, :date, :link)');
                $params = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'img' => $filePath,
                    'date' => $date,
                    'link' => $_POST['link']
                ];
            }
            else if($table == 'services'){
                $request = $db->prepare('INSERT INTO ' . $prefix . $table . ' (`title`, `description`) VALUES (:title, :description)');
                $params = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description']
                ];
            }
            else if($table == 'contact'){
                $request = $db->prepare('INSERT INTO ' . $prefix . $table . ' (`date`, `dateTime`, `email`, `object`, `message`) VALUES (:date, :dateTime, :email, :object, :message)');
                $params = [
                    'date' => $date,
                    'dateTime' => $dateTime,
                    'email' => $_POST['email'],
                    'object' => $_POST['object'],
                    'message' => $_POST['message']
                ];
            }
            else{
                writeServiceMessage('Cette table n\'existe pas');
                die();
            }
            // les données sont executées, un message d'avertissement est retournée et on retourne à la table de départ
            var_dump($table);
            if ($request->execute($params)) {
                writeServiceMessage('Créée avec succès!');
                if($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact'){
                    header('Location: connected.php?page=' . $table . '&table=' . $table . '&action=list');
                    //header('Location: /portfolio_crud/home.php?table=' . $table . '&action=list');
                    exit();
                }
                else {
                    writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
                    header('Location: connected.php?page=index&table=index&action=list');
                }
                die();
            }
        }
        else{
            writeServiceMessage('Formulaire invalide');
            header('Location: connected.php?page=index&table=index&action=list');
        }
    }
    else{
        if($table == 'index'){
            $content = getFormIndex(null,$action);
        }
        else if($table == 'presentation'){
            var_dump($action);
            $content = getFormPresentation(null,$action);
        }
        else if($table == 'works'){
            $content = getFormWorks(null,$action);
        }
        else if($table == 'services'){
            $content = getFormServices(null, $action);
        }
        else if($table == 'contact'){
            $content = getFormContact(null,$action);
        }
        else{
            writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
            $content = getFormIndex(null,$action);
        }
    }

    return $content;
}

// on supprime une ligne du tableau approprié
function deletedataLine($db, $table){
    global $prefix;
    // si un id n'a pas été envoyé, on retourne une erreur 404 et en fonction de la table, on change le contenu pour afficher un message et un lien vers la dite table, sinon, on execute la commande demandée
    if (!isset($_GET['id'])) {
        http_response_code(404);
        if($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact'){
            $content = 'Un id est nécessaire pour supprimer ces données de la base de donnée. Veuillez <a href="index.php?table=' . $table . '&action=list">retourner à la liste ' . $table . '</a>';
//            $content = 'Un id est nécessaire pour supprimer ces données de la base de donnée. Veuillez <a href="/portfolio_crud/index.php?table=' . $table . '&action=list">retourner à la liste ' . $table . '</a>';
        }
        else {
            $content = 'Un id est nécessaire pour supprimer ces données de la base de donnée. Veuillez <a href="index.php?table=index&action=list">retourner à la liste index</a>';
        }

        return $content;
    }
    else {
        $request = $db->prepare('DELETE FROM ' . $prefix . $table . ' WHERE `id`=:id');
        $params = ['id' => $_GET['id']];
        if ($request->execute($params)) {
            if($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact'){
                writeServiceMessage('donnée de la table ' . $table . ' supprimée avec succès!');
                header('Location: connected.php?table=' . $table . '&action=list');
                //header('Location: /portfolio_crud/connected.php?table=' . $table . '&action=list');
                exit();
            }
            else {
                writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
                header('Location: connected.php?table=index&action=list');
            }
            die();
        }
    }
}

// on met à jour une ligne du tableau approprié
function updatedataLine($db, $table, $action)
{
    global $prefix;
    // si un id n'a pas été envoyé, on retourne une erreur 404 et en fonction de la table, on change le contenu pour afficher un message et un lien vers la dite table, sinon, on poursuit la vérification de la mise à jour de la ligne
    if (!isset($_GET['id'])){
        http_response_code(404);
        if($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact'){
            //$content = 'Mauvaise requête, impossible de mettre à jour sans avoir un id. Veuillez <a href="/portfolio_crud/home.php?table=' . $table . '&action=list">retourner à la liste ' . $table . '</a>';
            $content = 'Mauvaise requête, impossible de mettre à jour sans avoir un id. Veuillez <a href="connected.php?table=' . $table . '&action=list">retourner à la liste ' . $table . '</a>';
        }
        else {
            writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
            header('Location: connected.php?table=index&action=list');
            //header('Location: /portfolio_crud/home.php?table=index&action=list');
        }
    }
    else {
        // si un formulaire est envoyé, on peut continuer la mise à jour de la ligne, sinon on retourne la table de départ ou un message d'erreur 404 (si on ne retrouve pas la dite ligne de donnée)
        if (isFormSubmit()) {
            // si le formulaire est valide, on peut continuer la mise à jour de la ligne, sinon on retourne la table de départ avec un message d'avertissement
            if (isFormValid()) {
                //todo image false in array $_files
                //var_dump($_GET);
                //var_dump($_POST);
                var_dump($_FILES['image']);
                if($table == 'index'){
                    $request = $db->prepare('UPDATE ' . $prefix . $table . ' SET `title`=:title, `response`=:response, `image`=:image WHERE `id`=:id');
                    $params = [
                        'title' => $_POST['title'],
                        'response' => $_POST['response'],
                        'image' => $_FILES['image']
                    ];
                }
                else if($table == 'presentation'){
                    $request = $db->prepare('UPDATE ' . $prefix . $table . ' SET `title`=:title, `description`=:description WHERE `id`=:id');
                    $params = [
                        'title' => $_POST['title'],
                        'description' => $_POST['description'],
                        'id' => $_GET['id']
                    ];
                }
                else if($table == 'works'){
                    $request = $db->prepare('UPDATE ' . $prefix . $table . ' SET `img`=:img, `title`=:title, `description`=:description, `date`=:date, `link`=:link WHERE `id`=:id');
                    $params = [
                        'img' => $_POST['img'],
                        'title' => $_POST['title'],
                        'description' => $_POST['description'],
                        'date' => $_POST['date'],
                        'link' => $_POST['link'],
                        'id' => $_GET['id']
                    ];
                }
                else if($table == 'services'){
                    $request = $db->prepare('UPDATE ' . $prefix . $table . ' SET `title`=:title, `description`=:description WHERE `id`=:id');
                    $params = [
                        'title' => $_POST['title'],
                        'description' => $_POST['description'],
                        'id' => $_GET['id']
                    ];
                }
                else if($table == 'contact'){
                    $request = $db->prepare('UPDATE ' . $prefix . $table . ' SET `date`=:date, `email`=:email, `object`=:object, `message`=:message WHERE `id`=:id');
                    $params = [
                        'date' => $_POST['date'],
                        'email' => $_POST['email'],
                        'object' => $_POST['object'],
                        'message' => $_POST['message'],
                        'id' => $_GET['id'],
                    ];
                }

                // les données sont executées, un message d'avertissement est retournée et on retourne à la table de départ
                if ($request->execute($params)) {
                    if($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact'){
                        writeServiceMessage('La table ' . $table . ' a été mise à jour avec succès.');
                        //header('Location: /portfolio_crud/home.php?table=' . $table . '&action=list');
                        header('Location: connected.php?table=' . $table . '&action=list');
                    }
                    else {
                        writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
                        //header('Location: /portfolio_crud/home.php?table=index&action=list');
                        header('Location: connected.php?table=index&action=list');
                    }
                    die();
                }
            }
        }

        $request = $db->prepare('SELECT * from ' . $prefix . $table . ' WHERE `id`=:id');
        $request->execute(['id' => $_GET['id']]);
        $lines = $request->fetchAll();
        if (!count($lines)){
            http_response_code(404);
            $content = 'Les données sont introuvables <a href="index.php">Retour à la liste index</a>';
//            $content = 'Les données sont introuvables <a href="/portfolio_crud/index.php">Retour à la liste index</a>';
        }
        else if($table == 'index'){
            $content = getFormIndex($lines[0], $action);
        }
        else if($table == 'presentation'){
            $content = getFormPresentation($lines[0], $action);
        }
        else if($table == 'works'){
            $content = getFormWorks($lines[0],$action);
        }
        else if($table == 'services'){
            //$lines = fetchIncrementation($db);
            $content = getFormServices($lines[0], $action);
        }
        else if($table == 'contact'){
            $content = getFormContact($lines[0], $action);
        }
    }

    return $content;
}

?>

