<?php

// nav créer une réalisation
function getNavWorks() {
    $nav = '
    <nav>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=works&action=create">Créer un travail</a>
        </li>
    </ul>
    </nav>';

    return $nav;
}

// liste des réalisations
function getTableWorks($lines) {
    $table = '<h1>Liste des réalisations</h1>
    <table class="table">
    <thead><tr>  <th>Id</th> <th>Title</th>   <th>Description</th>  <th>Image</th>  <th>Date</th>   <th>Link</th>   <th>Action</th> </tr></thead>
    <tbody>';

    foreach ($lines as $line) {
        $table .= '<tr>
            <td>' . $line['id'] . '</td>
            <td>' . $line['title'] . '</td>
            <td>' . $line['description'] . '</td>
            <td>' . ($line['img'] !== null ? '<img class="works-image" src="' . $line['img'] . '" alt="work"/>' : 'No image') . '</td>
            <td>' . $line['date'] . '</td>
            <td><a href="' . $line['link'] . '">Lien vers le projet</a></td>
            <td>
                <a class="btn btn-danger" href="?table=works&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                <a class="btn btn-primary" href="?table=works&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
    }

    $table .= '</tbody>
    </table>';

    return $table;
}

// formulaire create et update des réalisations
function getFormWorks($works, $action){
    $form = '';

    if ($action == 'create') {
        $form.= '<h1>Créer une réalisation</h1>';
    }
    else if($action == 'update'){
        $form.= '<h1>Modifier une réalisation</h1>';
    }

    $form.= '
        <form method="post" enctype="multipart/form-data">
        <div class="form-group" hidden>
            <label for="id">Id : </label>
            <input type="text" class="form-control" name="id" id="id" value="' . ($works ? $works['id'] : '') . '">
        </div>
        <div class="form-group">
            <label for="title">Titre : </label>
            <br>
            <input type="textarea" name="title" id="title" value="' . ($works ? $works['title'] : '') . '"/>
        </div>
        <div class="form-group">
            <label for="description">Description : </label>
            <br>
            <input type="textarea" name="description" id="description" value="' . ($works ? $works['description'] : '') . '"/>
        </div>
        <div class="form-group">
            <label for="image">Image : </label>
            <br>
            <input type="file" name="image" id="image" ' . ($works ? 'disabled="true"': '') . ' value=""/>';
    if ($works['img'] != null && $action == 'update') {
        $form.= '<img class="works-image" src="' . ($works['img'] ? $works['img'] : '') . '" alt="work"/>';
    }
    $form.= '
            <script src="image.js"></script>
            <!-- <script src="images/images.js"></script> -->
        </div>
        <div class="form-group">
            <label for="date">Date : </label>
            <br>
            <input type="date" name="date" id="date" value="' . ($works ? $works['date'] : '') . '"/>
        </div>
        <div class="form-group">
            <label for="link">Lien : </label>
            <br>
            <input type="url" name="link" id="link" value="' . ($works ? $works['link'] : '') . '"/>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">envoyer</button>
        </div>
    </form>';
    return $form;
}

?>