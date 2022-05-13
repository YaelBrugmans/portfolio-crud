<?php

function getNavServices() {
    $nav = '
    <nav class="action">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=services&action=create">Créer un services</a>
        </li>
    </ul>
    </nav>';

    return $nav;
}

// liste des services
function getTableServices($lines) {
    $table = '<h1>Liste des services</h1>
    <table class="table">
    <thead><tr>    <th>Id</th> <th>Title</th>   <th>Description</th>    <th>Action</th> </tr></thead>
    <tbody>';
    
    foreach ($lines as $line) {
        $table .= '<tr>
            <td>' . $line['id'] . '</td>
            <td>' . $line['title'] . '</td>
            <td>' . $line['description'] . '</td>
            <td>
                    <a class="btn btn-danger" href="?table=services&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                    <a class="btn btn-primary" href="?table=services&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
    }
    
    $table .= '</tbody>
    </table>';

    return $table;
}

function getFormServices($services, $action){
    $form = '';
        if ($action == 'create') {
            $form .= '<h1>Créer un services</h1>';
        }
        else if($action == 'update'){
            $form .= '<h1>Modifier un services</h1>';
        }
        $form .= '
        <form method="post" enctype="multipart/form-data">
        <div class="form-group" hidden>
            <label for="id">Id : </label>
            <input type="text" class="form-control" name="id" id="id" value="' . ($services ? $services['id'] : '') . '">
        </div>
        <div class="form-group">
            <label for="title">Titre : </label>
            <input class="form-control" name="title" id="title">' . ($services ? $services['title'] : '') . '</input>
        </div>
        <div class="form-group">
            <label for="description">Description : </label>
            <textarea class="form-control" name="description" id="description">' . ($services ? $services['description'] : '') . '</textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">envoyer</button>
        </div>
    </form>';
    return $form;
}

function fetchIncrementation(PDO $db): array {
    $req = $db->prepare('SELECT DISTINCT `incrementation` from `services`');
    $req->execute();
    $results = $req->fetchAll();
    return array_map(function($incrementationWrapper){ return $incrementationWrapper['incrementation'];}, $results);
}

?>