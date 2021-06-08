<?php

// nav créer une réalisation
function getNavTravaux() {
    $nav = '
    <nav>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=travaux&action=create">Créer un travail</a>
        </li>
    </ul>
    </nav>';

    return $nav;
}

// liste des réalisations
function getTableTravaux($lines) {
    $table = '<h1>Liste des réalisations</h1>
    <table class="table">
    <thead><tr>  <th>id</th> <th>image</th>   <th>description</th>   <th>action</th> </tr></thead>
    <tbody>';
    
    foreach ($lines as $line) {
        $table .= '<tr>
            <td>' . $line['id'] . '</td>
            <td>' . ($line['image_travaux'] !== null ? '<img class="travaux-image" src="../portfolio_crud/' . $line['image_travaux'] . '" />' : '') . '</td>
            <td>' . $line['description'] . '</td>
            <td>
                <a class="btn btn-danger" href="?table=travaux&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                <a class="btn btn-primary" href="?table=travaux&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
    }

    $table .= '</tbody>
    </table>';

    return $table;
}

// formulaire create et update des réalisations
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
            <label for="image">image:</label>
            <br>
            <input type="file" name="image" id="image" ' . ($travaux ? 'disabled="true"': '') . '/>';
            if ($travaux && $action == 'update') {
                $form.= '<img class="travaux-image" src="../portfolio_crud/' . $travaux['image'] . '"/>';
            }
            $form.= '
            <script src="/portfolio_crud/images.js"></script>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">envoyer</button>
        </div>
    </form>';
    return $form;
}

?>
