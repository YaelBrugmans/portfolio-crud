<?php

// nav créer une réalisation
function getNavTravaux() {
    $nav = '
    <nav>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=`travaux`&action=create">Créer un travail</a>
        </li>
    </ul>
    </nav>';

    return $nav;
}

// retourne la liste des services
function getTableTravaux($lines) {
    $table = '<h1>Liste des réalisations</h1>
    <table class="table">
    <thead><tr>  <th>id</th> <th>image</th>   <th>description</th>   <th>action</th> </tr></thead>
    <tbody>';
    
    foreach ($lines as $line) {
        $table .= '<tr>
            <td>' . $line['id'] . '</td>
            <td>' . ($line['image_travaux'] !== null ? '<img class="image-travaux" src="' . $line['image_travaux'] . '" />' : '') . '</td>
            <td>' . $line['description'] . '</td>
            <td>
                <a class="btn btn-danger" href="?table=`travaux`&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                <a class="btn btn-primary" href="?table=`travaux`&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
    }

    $table .= '</tbody>
    </table>';

    return $table;
}

// crée un formulaire pour créer ou modifier un travail
function getFormTravaux($travaux, $action){
    $form = '';

    if ($action == 'create') {
        $form.= '<h1>Créer une réalisation</h1>';
        }
        else if($action == 'update'){
            $form.= '<h1>Modifier une réalisation</h1>';
        }

        $form.= '
        <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="description">description : </label>
            <br>
            <input type="textarea" name="description" id="description" value="' . ($travaux ? $travaux['description'] : '') . '"/>
        </div>
        <div class="form-group">
            <label for="image_travaux">image:</label>
            <br>
            <input type="file" name="image_travaux" id="image_travaux" ' . ($travaux ? 'disabled="true"': '') . '/>';
            if ($travaux && $action == 'update') {
                $form.= '<img class="travaux-travaux" src="' . $travaux['image_travaux'] . '"/>';
            }
            $form.= '
            <script src="image.js"></script>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">envoyer</button>
        </div>
    </form>';
    return $form;
}

?>