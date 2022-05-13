<?php

// navigation vers un lien pour créer un mail de la page contact
function getNavContact() {
    $nav = '
    <nav class="action">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="?table=contact&action=create">Créer un mail de contact</a>
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
    <thead><tr>    <th>Id</th> <th>Date</th>    <th>DateTime</th>  <th>Email</th>   <th>Object</th>  <th>Message</th>    <th>action</th> </tr></thead>
    <tbody>';
    
    foreach ($lines as $line) {
        $table .= '<tr>
            <td>' . $line['id'] . '</td>
            <td>' . $line['date'] . '</td>
            <td>' . $line['dateTime'] . '</td>
            <td>' . $line['email'] . '</td>
            <td>' . $line['object'] . '</td>
            <td>' . $line['message'] . '</td>
            <td>
                    <a class="btn btn-danger" href="?table=contact&action=delete&id=' . $line['id'] . '"><i class="fa fa-times"></i></a>
                    <a class="btn btn-primary" href="?table=contact&action=update&id=' . $line['id'] . '"><i class="fa fa-edit"></i></a>
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
    $date = date('Y-m-d');
    $dateTime = date('h:m:s');

        if ($action == 'create') {
            $form .= '<h1>Créer un mail de contact</h1>';
        }
        else if($action == 'update'){
            $form .= '<h1>Modifier un mail de contact</h1>';
        }

        $form .= '
        <form method="post" enctype="multipart/form-data">
            <div class="form-group" hidden>
                <label for="id">Id : </label>
                <input type="text" class="form-control" name="id" id="id" value="' . ($contact ? $contact['id'] : '') . '">
            </div>
            <div class="form-group" hidden>
                <label for="date">Date : </label>
                <input type="date" class="date" name="date" id="date" value="' . ($contact != null ? $contact['date'] : $date) . '"></input>
            </div>
            <div class="form-group" hidden>
                <label for="dateTime">Time : </label>
                <input type="time" class="dateTime" name="dateTime" id="dateTime" value="' . ($contact != null ? $contact['dateTime'] : $dateTime) . '">' . ($contact != null ? $contact['dateTime'] : $dateTime) . '</input>
            </div>
            <div class="form-group">
                <label for="email">Adresse email : </label>
                <input type="email" class="form-control" name="email" id="email" required>' . ($contact ? $contact['email'] : '') . '</input>
            </div>
            <div class="form-group">
                <label for="object">Objet : </label>
                <input type="text" class="form-control" name="object" id="object">' . ($contact ? $contact['object'] : '') . '</input>
            </div>
            <div class="form-group">
                <label for="message">Message : </label>
                <textarea class="form-control" name="message" id="message">' . ($contact ? $contact['message'] : '') . '</textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">envoyer</button>
            </div>
        </form>';

    return $form;
}

?>