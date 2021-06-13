<?php

// les diverses fonctions nécessaires à chaque page pour son fonctionnement
include 'login.php';
include 'accueil.php';
include 'presentation.php';
include 'travaux.php';
include 'services.php';
include 'contact.php';

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
    </head>';

    return $head;
}

// navigation par défaut de chaque page, vers chaque page
function getNav() {
    $nav = '
    <header>
        <nav>
            <ul class="nav first-nav">
                <!--   links to pages Accueil.html, presentation.html, travaux.html, services.html, contact.html        -->
                <li class="nav-item">
                    <a class="nav-link" href="?table=`index`&action=list">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?table=`presentation`&action=list">Présentation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?table=`travaux`&action=list">Travaux Réalisés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?table=`services`&action=list">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?table=`contact`&action=list">Contact</a>
                </li>
            </ul>
        </nav>
    </header>
    ';

    return $nav;
}

// le footer de chaque page
function getFooter() {
    $nav = '
    <footer>
        <p>Yael-Brugmans-<a class="nav-link" href="mailto:yaelbrugmans1998@gmail.com">yaelbrugmans1998@gmail.com</a>
        </p>
    </footer>
    ';

    return $nav;
}

// si un formulaire est posté, on vérifie qu'il a été posté correctement
function isFormSubmit() : bool {
    $sub = "";

    if(isset($_POST['titre_index'])){
        $sub = isset($_POST['titre_index']);
    }
    else if(isset($_POST['titre'])){
        $sub = isset($_POST['titre']);
    }
    else if(isset($_POST['description']) ){
        $sub = isset($_POST['description']);
    }
    else if(isset($_POST['line'])){
        $sub = isset($_POST['line']);
    }
    else if(isset($_POST['mail_expediteur'])){
        $sub = isset($_POST['mail_expediteur']);
    }

    return $sub; // Si on a au moins un POST correspondant, on peut valider le formulaire.
}

// on vérifie si le formulaire posté correctement a été rempli correctement
function isFormValid() : bool {
    // les données ne doivent pas être vides
    $form =  
        ((!empty($_POST['titre_index'])) || (!empty($_POST['titre'])) || (!empty($_POST['description'])) || (!empty($_POST['line'])) || (!empty($_POST['mail_expediteur'])));

    // si il y a un forme, qu'une image a bien été envoyé et que le fichier n'a strictement aucune erreur, 
    if ($form && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // si la taille de l'image excède 1 000 000 de pixels, on retourne un message d'erreur
        if ($_FILES['image']['size'] > 1000000) {
            writeServiceMessage("L'image est trop grande");
            $form = false;
        }
        // si l'image est dans un autre format que jpg, jpeg et png, on retourne un message d'erreur
        $imageParameters = pathinfo($_FILES['image']['name']);
        $imageExtension = $imageParameters['extension'];
        $extensionsAllowed = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG');
        if (!in_array($imageExtension, $extensionsAllowed)) {
            writeServiceMessage("Seulement les extensions " . implode(", ", $extensionsAllowed) . " sont autorisées");
            $form = false;
        }
    }

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
function uploadImage() {
    $filePath = 'images/' . basename($_FILES['image']['name']);
    if(move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
        return $filePath;
    }
    return null;
}

// retourne la table demandée en fonction de la page, sinon on affiche un message d'erreur et la table index est sélectionnée par défaut
function displayTable($db, $table) {
    $request = $db->prepare('SELECT * FROM ' . $table . '');
    $request->execute();
    $lines = $request->fetchAll();

    if($table == '`index`'){
        $content = getTableIndex($lines);
    }
    else if($table == '`presentation`'){
        $content = getTablePresentation($lines);
    }
    else if($table == '`travaux`'){
        $content = getTableTravaux($lines);
    }
    else if($table == '`services`'){
        $content = getTableServices($lines);
    }
    else if($table == '`contact`'){
        $content = getTableContact($lines);
    }
    else{
        writeServiceMessage('Une erreur est survenue, lors de la sélection de la page');
        $content = getTableIndex($lines);
    }

    return $content;
}

// on crée une nouvelle ligne dans un tableau
function createdataLine($db, $table, $action){
    // si un formulaire est envoyé, on peut continuer la création de la ligne, sinon on retourne le formulaire avec aucune donnée
    if(isFormSubmit()) {
        // si le formulaire est valide, on peut continuer la création de la ligne, sinon on retourne le formulaire avec aucune donnée        
        if (isFormValid()) {
            // on selectionne la table appropriée, et on prépare les données
            if($table == '`index`'){
                $filePath = uploadImage();
                $request = $db->prepare('INSERT INTO ' . $table . ' (`titre_index`, `reponse`, `image_index`) VALUES (:titre_index, :reponse, :image_index)');
                $params = [
                    'titre_index' => $_POST['titre_index'],
                    'reponse' => $_POST['reponse'],
                    'image_index' => $filePath
                ];
            }
            else if($table == '`presentation`'){
                $request = $db->prepare('INSERT INTO ' . $table . ' (`date_presentation`, `texte_presentation`, `titre`) VALUES (:date_presentation, :texte_presentation, :titre)');
                $params = [
                'date_presentation' => $_POST['date_presentation'],
                'texte_presentation' => $_POST['texte_presentation'],
                'titre' => $_POST['titre']
                ];
            }
            else if($table == '`travaux`'){
                $filePath = uploadImage();
                $request = $db->prepare('INSERT INTO ' . $table . ' (`image_travaux`, `description`) VALUES (:image_travaux, :description)');
                $params = [
                'image_travaux' => $filePath,
                'description' => $_POST['description']
                ];
            }
            else if($table == '`services`'){
                $request = $db->prepare('INSERT INTO ' . $table . ' (`line`, `incrementation`) VALUES (:line, :incrementation)');
                $params = [
                'line' => $_POST['line'],
                'incrementation' => $_POST['incrementation']
                ];
            }
            else if($table == '`contact`'){
                $date = date("Y/m/d");
                writeServiceMessage($date);
                $request = $db->prepare('INSERT INTO ' . $table . ' (`mail_expediteur`, `objet`, `contact_message`, `contact_date`) VALUES (:mail_expediteur, :objet, :contact_message, :contact_date)');
                $params = [
                'mail_expediteur' => $_POST['mail_expediteur'],
                'objet' => $_POST['objet'],
                'contact_message' => $_POST['contact_message'],
                'contact_date' => $date
                ];
            }
            else{
                writeServiceMessage('Cette table n\'existe pas');
                die();
            }
            // les données sont executées, un message d'avertissement est retournée et on retourne à la table de départ
            if ($request->execute($params)) {
                writeServiceMessage('Créée avec succès!');
                if($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`'){
                    header('Location: connected.php?table=' . $table . '&action=list');
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
    else{
        if($table == '`index`'){
            $content = getFormIndex(null,$action);
        }
        else if($table == '`presentation`'){
            $content = getFormPresentation(null,$action);
        }
        else if($table == '`travaux`'){
            $content = getFormTravaux(null,$action);
        }
        else if($table == '`services`'){
            $content = getFormServices(null, $action);
        }
        else if($table == '`contact`'){
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
    // si un id n'a pas été envoyé, on retourne une erreur 404 et en fonction de la table, on change le contenu pour afficher un message et un lien vers la dite table, sinon, on execute la commande demandée
    if (!isset($_GET['id'])) {
        http_response_code(404);
        if($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`'){
            $content = 'Un id est nécessaire pour supprimer ces données de la base de donnée. Veuillez <a href="index.php?table=' . $table . '&action=list">retourner à la liste ' . $table . '</a>';
        }
        else {
            $content = 'Un id est nécessaire pour supprimer ces données de la base de donnée. Veuillez <a href="index.php?table=index&action=list">retourner à la liste index</a>';
        }

        return $content;
    }
    else {
        $request = $db->prepare('DELETE FROM ' . $table . ' WHERE `id`=:id');
        $params = ['id' => $_GET['id']];
        if ($request->execute($params)) {
            if($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`'){
                writeServiceMessage('donnée de la table ' . $table . ' supprimée avec succès!');
                header('Location: connected.php?table=' . $table . '&action=list');
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
    // si un id n'a pas été envoyé, on retourne une erreur 404 et en fonction de la table, on change le contenu pour afficher un message et un lien vers la dite table, sinon, on poursuit la vérification de la mise à jour de la ligne
    if (!isset($_GET['id'])){
        http_response_code(404);
        if($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`'){
            $content = 'Mauvaise requête, impossible de mettre à jour sans avoir un id. Veuillez <a href="connected.php?table=' . $table . '&action=list">retourner à la liste ' . $table . '</a>';
        }
        else {
            writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
            header('Location: connected.php?table=index&action=list');
        }
    } 
    else {
        // si un formulaire est envoyé, on peut continuer la mise à jour de la ligne, sinon on retourne la table de départ ou un message d'erreur 404 (si on ne retrouve pas la dite ligne de donnée)
        if (isFormSubmit()) {
            // si le formulaire est valide, on peut continuer la mise à jour de la ligne, sinon on retourne la table de départ avec un message d'avertissement
            if (isFormValid()) {
                $filePath = uploadImage();
                if($table == '`index`'){
                    $request = $db->prepare('UPDATE ' . $table . ' SET `titre_index`=:titre_index, `reponse`=:reponse, `image_index`=:image_index WHERE `id`=:id');
                    $params = [
                        'titre_index' => $_POST['titre_index'],
                        'reponse' => $_POST['titre_index'],
                        'image_index' => $filePath,
                        'id' => $_GET['id']
                    ];
                }
                else if($table == '`presentation`'){
                    $request = $db->prepare('UPDATE ' . $table . ' SET `date_presentation`=:date_presentation, `texte_presentation`=:texte_presentation, `titre`=:titre WHERE `id`=:id');
                    $params = [
                    'date_presentation' => $_POST['date_presentation'],
                    'texte_presentation' => $_POST['texte_presentation'],
                    'titre' => $_POST['titre'],
                    'id' => $_GET['id']
                    ];
                }
                else if($table == '`travaux`'){
                    $request = $db->prepare('UPDATE ' . $table . ' SET `image_travaux`=:image_travaux, `description`=:description WHERE `id`=:id');
                    $params = [
                        'description' => $_POST['description'],
                        'image_travaux' => $filePath,
                        'id' => $_GET['id']
                    ];
                }
                else if($table == '`services`'){
                    $request = $db->prepare('UPDATE ' . $table . ' SET `incrementation`=:incrementation, `line`=:line WHERE `id`=:id');
                    $params = [ 
                        'line' => $_POST['line'],
                        'incrementation' => $_POST['incrementation'],
                        'id' => $_GET['id']
                    ];
                }
                else if($table == '`contact`'){
                    $request = $db->prepare('UPDATE ' . $table . ' SET `mail_expediteur`=:mail_expediteur, `mail_destinataire`=:mail_destinataire, `objet`=:objet, `contact_message`=:contact_message, `contact_date`=:contact_date, WHERE `id`=:id');
                    $params = [ 
                        'mail_expediteur' => $_POST['mail_expediteur'],
                        'mail_destinataire' => $_POST['mail_destinataire'],
                        'objet' => $_POST['objet'],
                        'contact_message' => $_POST['contact_message'],
                        'contact_date' => $_POST['contact_date'],
                        'id' => $_GET['id'],
                    ];
                }
                
                // les données sont executées, un message d'avertissement est retournée et on retourne à la table de départ
                if ($request->execute($params)) {
                    if($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`'){
                        writeServiceMessage('La table ' . $table . ' a été mise à jour avec succès.');
                        header('Location: connected.php?table=' . $table . '&action=list');
                    }
                    else {
                        writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
                        header('Location: connected.php?table=index&action=list');
                    }
                    die();
                }
            }
        }

        $request = $db->prepare('SELECT * from ' . $table . ' WHERE `id`=:id');
        $request->execute(['id' => $_GET['id']]);
        $lines = $request->fetchAll();
        if (!count($lines)){
            http_response_code(404);
            $content = 'Les données sont introuvables <a href="connected.php?table=`index`&action=list">Retour à la liste index</a>';
        }
        else if($table == '`index`'){
            $content = getFormIndex($lines[0], $action);
        }
        else if($table == '`presentation`'){
            $content = getFormPresentation($lines[0], $action);
        }
        else if($table == '`travaux`'){
            $content = getFormTravaux($lines[0],$action);
        }
        else if($table == '`services`'){
            $lines = fetchIncrementation($db);
            $content = getFormServices($lines[0], $action);
        }
        else if($table == '`contact`'){
            $content = getFormContact($lines[0], $action);
        }
    }

    return $content;
}

?>

