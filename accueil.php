<?php

// navigation vers un lien pour créer une donnée index
function getNavIndex() {
    $nav = '
    <nav class="action">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=index&action=create">Créer une donnée de la page index</a>
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
    <thead><tr>    <th>Id</th> <th>Titre</th>  <th>Réponse</th>   <th>Image</th>    <th>Action</th> </tr></thead>
    <tbody>';

    foreach ($lines as $line) {
        $table .= '
        <tr>
            <td>' . $line['id'] . '</td>
            <td>' . $line['title'] . '</td>
            <td>' . $line['response'] . '</td>
            <td>' . ($line['image'] != null || $line['image'] != '' ? '<img class="index-image" src="' . $line['image'] . '" alt="can\'t get image"/>' : 'No image') . '</td>
            <td>
                    <a class="btn btn-danger" href="?table=index&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                    <a class="btn btn-primary" href="?table=index&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
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
            <div class="form-group" hidden>
                <label for="id">Id : </label>
                <input type="text" class="form-control" name="id" id="id" value="' . ($index ? $index['id'] : '') . '">
            </div>
            <div class="form-group">
                <label for="title">Titre : </label>
                <input type="text" class="form-control" name="title" id="title" value="' . ($index ? $index['title'] : '') . '">
            </div>
            <div class="form-group">
                <label for="response">Réponse : </label>
                <input type="text" class="form-control" name="response" id="response" value="' . ($index ? $index['response'] : '') . '"></input>
            </div>
            <div class="form-group">
                <label for="image">Image : </label>
                <br>
                <input type="file" name="image" id="image" ' . ($index ? 'disabled="true"': '') . '/>';
    if ($index && $action == 'update') {
        $form.= '<img class="index-image" src="' . $index['image'] . '" alt="index-image"/>';
    }
    $form.= '
                <script src="image.js"></script>
                <!-- <script src="../images/images.js"></script> -->
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">envoyer</button>
            </div>
        </form>';

    return $form;
}

?>