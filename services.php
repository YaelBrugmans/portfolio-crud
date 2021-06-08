<?php

function getNavServices() {
    $nav = '
    <nav class="action">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=service&action=create">Créer un service</a>
        </li>
    </ul>
    </nav>';

    return $nav;
}

// liste des services
function getTableServices($lines) {
    $table = '<h1>Liste des services</h1>
    <table class="table">
    <thead><tr>    <th>id</th> <th>service</th>  <th>incrementation</th>    <th>action</th> </tr></thead>
    <tbody>';
    
    foreach ($lines as $line) {
        $table .= '<tr>
            <td>' . $line['id'] . '</td>
            <td>' . $line['line'] . '</td>
            <td>' . $line['incrementation'] . '</td>
            <td>
                    <a class="btn btn-danger" href="?table=service&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                    <a class="btn btn-primary" href="?table=service&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
    }
    
    $table .= '</tbody>
    </table>';

    return $table;
}

function getFormServices($service, $action){
    $form = '';
        if ($action == 'create') {
            $form .= '<h1>Créer un service</h1>';
        }
        else if($action == 'update'){
            $form .= '<h1>Modifier un service</h1>';
        }
        $form .= '
        <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="line">Service : </label>
            <textarea class="form-control" name="line" id="line">' . ($service ? $service['line'] : '') . '</textarea>
        </div>
        <div class="form-group">
            <label for="incrementation">Incrementation : </label>
            <select class="form-control" name="incrementation" id="incrementation">';
            foreach ($service as $incrementation) {
                $form .= '<option value="' . $incrementation . '">' . $incrementation . '</option>';
            }
            $form .= '</select>
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
