<?php

// navigation vers un lien pour créer une donnée index
function getNavIndex() {
    $nav = '
    <nav class="action">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=`index`&action=create">Créer une donnée de la page index</a>
        </li>
    </ul>
    </nav>';

    return $nav;
}

// retourne la liste de la page index
function getTableIndex($lines) {

    $table = '
    <h1>Liste index</h1>
    <table class="table">
    <thead><tr><th>id</th><th>titre</th><th>réponse</th><th>image_index</th><th>action</th></tr></thead>
    <tbody>';
    
    foreach ($lines as $line) {
        $table .= '
        <tr>
            <td>' . $line['id'] . '</td>
            <td>' . $line['titre_index'] . '</td>
            <td>' . $line['reponse'] . '</td>
            <td>' . ($line['image_index'] !== null ? '<img class="image-index" src="' . $line['image_index'] . '" />' : '') . '</td>
            <td>
                    <a class="btn btn-danger" href="?table=`index`&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                    <a class="btn btn-primary" href="?table=`index`&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
    }
    
    $table .= '</tbody>
    </table>';

    return $table;
}

// crée un formulaire pour créer ou modifier une donnée index
function getFormIndex($index, $action){
    $form = '';

        if ($action == 'create') {
            $form .= '<h1>Créer une donnée de l\'index</h1>';
        }
        else if($action == 'update'){
            $form .= '<h1>Modifier une donnée de l\'index</h1>';
        }

        $form .= '
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre_index">Titre : </label>
                <input type="text" class="form-control" name="titre_index" id="titre_index" value="' . ($index ? $index['titre_index'] : '') . '">
            </div>
            <div class="form-group">
                <label for="reponse">Réponse : </label>
                <input type="text" class="form-control" name="reponse" id="reponse" value="' . ($index ? $index['reponse'] : '') . '">
            </div>
            <div class="form-group">
                <label for="image">Image : </label>
                <br>
                <input type="file" name="image" id="image" ' . ($index ? 'disabled="true"': '') . '/>';
                if ($index && $action == 'update') {
                    $form.= '<img class="image-index" src="' . $index['image_index'] . '"/>';
                }
                $form.= '
                <script src="image.js"></script>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Envoyer</button>
            </div>
        </form>';

    return $form;
}

?>