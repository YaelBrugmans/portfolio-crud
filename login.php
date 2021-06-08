<?php

// affichage du form pour la connection
function formLogin() {
    $form = '
    <form action="authentification.php" method="post">
        <div class="form-group">
            <label for="username">Nom d\'utilisateur : </label>
            <input type="text" class="form-control" id="username" name="username" required>
            <p>*Pas de caractères spéciaux, ni de chiffres</p>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe : </label>
            <input type="password" class="form-control" id="password" name="password" required>
            <p>*Maximum 12 caractères</p>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">se connecter</button>
        </div>
	</form>';

	return $form;
}

// si une erreur est survenue lors du formulaire login, un message d'erreur est envoyé et on retourne au formulaire login
function formError() {
    writeServiceMessage('Le nom d\'utilisateur ou le mot de passe incorrect');
    $content = formLogin();

    return $content;
}

?>
