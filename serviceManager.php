<?php

//$prefix = 'portfolio_';

// retourne la table demandée en fonction de la page, sinon on affiche un message d'erreur et la table index est sélectionnée par défaut
function displayTable($db, $table) {
    global $prefix;
    $request = $db->prepare('SELECT * FROM ' . $prefix . $table);
    $request->execute();
    $lines = $request->fetchAll();
    ;
    if($table === 'index'){
        $content = getTableIndex($lines);
    }
    else if($table === 'presentation'){
        writeServiceMessage('Une erreur est survenue, lors de la sélection de la page');
        $content = getTablePresentation($lines);
    }
    else if($table === 'works'){
        writeServiceMessage('Une erreur est survenue, lors de la sélection de la page');
        $content = getTableWorks($lines);
    }
    else if($table === 'services'){
        writeServiceMessage('Une erreur est survenue, lors de la sélection de la page');
        $content = getTableServices($lines);
    }
    else if($table === 'contact'){
        writeServiceMessage('Une erreur est survenue, lors de la sélection de la page');
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
    global $prefix;
    // si un formulaire est envoyé, on peut continuer la création de la ligne, sinon on retourne le formulaire avec aucune donnée
    if(isFormSubmit()) {
        // si le formulaire est valide, on peut continuer la création de la ligne, sinon on retourne le formulaire avec aucune donnée
        if (isFormValid()) {
            $filePath = uploadImage();
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
                    'description' => $_POST['description']
                ];
            }
            else if($table == 'works'){
                $request = $db->prepare('INSERT INTO ' . $prefix . $table . ' (`title`, `description`, `img`, `date`, `link`) VALUES (:title, :description, :img, :date, :link)');
                $params = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'img' => $filePath,
                    'date' => $_POST['date'],
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
                $request = $db->prepare('INSERT INTO ' . $prefix . $table . ' (`date`, `email`, `object`, `message`) VALUES (:date, :email, :object, :message)');
                $params = [
                    'date' => $_POST['date'],
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
            if ($request->execute($params)) {
                writeServiceMessage('Créée avec succès!');
                if($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact'){
                    header('Location: connected.php?table=' . $table . '&action=list');
                    //header('Location: /portfolio_crud/home.php?table=' . $table . '&action=list');
                    exit();
                }
                else {
                    writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
                    header('Location: /portfolio_crud/connected.php?table=index&action=list');
                }
                die();
            }
        }
    }
    else{
        if($table == 'index'){
            $content = getFormIndex(null,$action);
        }
        else if($table == 'presentation'){
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
                //header('Location: /portfolio_crud/home.php?table=' . $table . '&action=list');
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
            $content = 'Mauvaise requête, impossible de mettre à jour sans avoir un id. Veuillez <a href="/portfolio_crud/home.php?table=' . $table . '&action=list">retourner à la liste ' . $table . '</a>';
        }
        else {
            writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
            header('Location: home.php?table=index&action=list');
            //header('Location: /portfolio_crud/home.php?table=index&action=list');
        }
    }
    else {
        // si un formulaire est envoyé, on peut continuer la mise à jour de la ligne, sinon on retourne la table de départ ou un message d'erreur 404 (si on ne retrouve pas la dite ligne de donnée)
        if (isFormSubmit()) {
            // si le formulaire est valide, on peut continuer la mise à jour de la ligne, sinon on retourne la table de départ avec un message d'avertissement
            if (isFormValid()) {
                if($table == 'index'){
                    $request = $db->prepare('UPDATE ' . $prefix . $table . ' SET `title`=:title, `response`=:response, `image`=:image WHERE `id`=:id');
                    $params = [
                        'title' => $_POST['title'],
                        'response' => $_POST['response'],
                        'image' => $_GET['image']
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
                        header('Location: /portfolio_crud/home.php?table=' . $table . '&action=list');
                    }
                    else {
                        writeServiceMessage('Un problème est survenu lors de la manipulation de la table');
                        header('Location: /portfolio_crud/home.php?table=index&action=list');
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
            $lines = fetchIncrementation($db);
            $content = getFormServices($lines[0], $action);
        }
        else if($table == 'contact'){
            $content = getFormContact($lines[0], $action);
        }
    }

    return $content;
}

?>