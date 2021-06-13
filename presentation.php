<?php

// navigation vers un lien pour créer une donnée de présentation
function getNavPresentation() {
    $nav = '
    <nav class="action">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=`presentation`&action=create">Créer une nouvelle ligne</a>
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
    <thead><tr>    <th>id</th> <th>titre</th> <th>date</th>  <th>texte</th>   <th>action</th> </tr></thead>
    <tbody>';
    
    foreach ($lines as $line) {
        $table .= '<tr>
            <td>' . $line['id'] . '</td>
            <td>' . $line['titre'] . '</td>
            <td>' . $line['date_presentation'] . '</td>
            <td>' . $line['texte_presentation'] . '</td>
            <td>
                    <a class="btn btn-danger" href="?table=`presentation`&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                    <a class="btn btn-primary" href="?table=`presentation`&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
    }
    
    $table .= '</tbody>
    </table>';

    return $table;
}

// crée un formulaire pour créer ou modifier une ligne du cv de présentation
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
                <label for="date_presentation">Date de la présentation (l\'année) : </label>
                <input type="text" class="form-control" name="date_presentation" id="date_presentation" value="' . ($presentation ? $presentation['date_presentation'] : '') . '">
            </div>
            <div class="form-group">
                <label for="titre">Titre : </label>
                <input type="text" class="form-control" name="titre" id="titre" value="' . ($presentation ? $presentation['titre'] : '') . '">
            </div>
            <div class="form-group">
                <label for="texte_presentation">Texte : </label>
                <textarea class="form-control" name="texte_presentation" id="texte_presentation">' . ($presentation ? $presentation['texte_presentation'] : '') . '</textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">envoyer</button>
            </div>
        </form>';

    return $form;
}

?>