<?php

// navigation vers un lien pour créer une donnée de présentation
function getNavPresentation() {
    $nav = '
    <nav class="action">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=contact&action=create">Créer une nouvelle ligne</a>
        </li>
    </ul>
    </nav>';

    return $nav;
}

// retourne la liste des données de présentation
function getTablePresentation($lines) {
    $table = '
    <h1>Liste des données de présentation</h1>
    <table class="table">
    <thead><tr>    <th>id</th> <th>titre</th>  <th>réponse</th>   <th>image_index</th>    <th>action</th> </tr></thead>
    <tbody>';
    
    foreach ($lines as $line) {
        $table .= '<tr>
            <td>' . $line['id'] . '</td>
            <td>' . $line['titre'] . '</td>
            <td>' . $line['texte_presenattion'] . '</td>
            <td>
                    <a class="btn btn-danger" href="?table=presentation&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                    <a class="btn btn-primary" href="?table=presentation&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
    }
    
    $table .= '</tbody>
    </table>';

    return $table;
}

// crée un formulaire pour créer ou modifier un mail de contact
function getFormPresentation($presentation, $action){
    $form = '';

        if ($action == 'create') {
            $form .= '<h1>Créer une donnée de présentation</h1>';
        }
        else if($action == 'update'){
            $form .= '<h1>Modifier une donnée de présentation</h1>';
        }

        $form .= '
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="date">Date de la présentation (l\'année) : </label>
                <input type="text" class="form-control" name="date" id="date">' . ($presentation ? $presentation['date_presentation'] : '') . '</input>
            </div>
            <div class="form-group">
                <label for="titre">Titre : </label>
                <input type="text" class="form-control" name="titre" id="titre">' . ($presentation ? $presentation['titre'] : '') . '</input>
            </div>
            <div class="form-group">
                <label for="texte">Texte : </label>
                <textarea class="form-control" name="texte" id="texte">' . ($presentation ? $presentation['texte_presentation'] : '') . '</textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">envoyer</button>
            </div>
        </form>';

    return $form;
}

?>