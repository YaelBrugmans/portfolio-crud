<?php

// navigation vers un lien pour créer un mail de la page contact
function getNavContact() {
    $nav = '
    <nav class="action">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=`contact`&action=create">Créer un mail de contact</a>
        </li>
    </ul>
    </nav>';

    return $nav;
}

// retourne la liste des mails de la page contact
function getTableContact($lines) {
    $table = '
    <h1>Liste des mails de contact</h1>
    <table class="table">
    <thead><tr>    <th>id</th> <th>expediteur</th>  <th>destinataire</th>   <th>objet</th>    <th>message</th> <th>date</th> </tr></thead>
    <tbody>';
    
    foreach ($lines as $line) {
        $table .= '<tr>
            <td>' . $line['id'] . '</td>
            <td>' . $line['mail_expediteur'] . '</td>
            <td>' . $line['mail_destination'] . '</td>
            <td>' . $line['objet'] . '</td>
            <td>' . $line['contact_message'] . '</td>
            <td>' . $line['contact_date'] . '</td>
            <td>
                    <a class="btn btn-danger" href="?table=`contact`&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                    <a class="btn btn-primary" href="?table=`contact`&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
    }
    
    $table .= '</tbody>
    </table>';

    return $table;
}

// crée un formulaire pour créer ou modifier un mail de contact
function getFormContact($contact, $action){
    $form = '';

        if ($action == 'create') {
            $form .= '<h1>Créer un mail de contact</h1>';
        }
        else if($action == 'update'){
            $form .= '<h1>Modifier un mail de contact</h1>';
        }

        $form .= '
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="mail_expediteur">Votre adresse mail : </label>
                <input type="text" class="form-control" name="mail_expediteur" id="mail_expediteur" value="' . ($contact ? $contact['mail_expediteur'] : '') . '">
            </div>
            <div class="form-group">
                <label for="objet">Objet : </label>
                <input type="text" class="form-control" name="objet" id="objet" value="' . ($contact ? $contact['objet'] : '') . '">
            </div>
            <div class="form-group">
                <label for="contact_message">Message : </label>
                <textarea class="form-control" name="contact_message" id="contact_message">' . ($contact ? $contact['contact_message'] : '') . '</textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">envoyer</button>
            </div>
        </form>';

    return $form;
}

?>