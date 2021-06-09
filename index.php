<?php

include 'functions.php';

session_start();

// si on reçoit une erreur de connexion, un avertissement de ce ou ces derniers sont retournés
if(isset($_GET['error'])){
    $content = formError();
}
else{
    $content = formLogin();
}

// la page à afficher en cas d'erreur
echo '<html>';
echo getHead();

echo '<body>';

    echo '<div class="container login">';
    $message = getServiceMessage();
    if($message) {
        echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
    }
    echo $content;
    echo '</div>';

echo '</body>
</html>';

