<?php

include 'functions.php';
include 'db.php';
// include 'api.php';

session_start();

// si un id et un nom d'utilisateur a été envoyé, la page complète avec le tableau s'affiche, sinon il est envoyé à une page d'erreur
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {

    // navigation entre les tables
    $table = 'index';
    $action = 'list';
    if(isset($_GET['table'])){
        $table = $_GET['table'];
        if(isset($_GET['action'])){
            $action = $_GET['action'];
        }
    }
    $content='';

    // retourne la table demandée
    if (($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact') && $action == 'list'){
        $content = displayTable($db, $table);
    }
    else if(($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact') && $action == 'create' ){
        $content = createdataLine($db, $table, $action);
    }
    else if (($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact') && $action == 'delete'){
        deletedataLine($db, $table);
    }
    else if (($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact') && $action == 'update'){
        $content = updatedataLine($db, $table, $action);
    }
    else if (($table == 'index' || $table == 'presentation' || $table == 'works' || $table == 'services' || $table == 'contact')){
        writeServiceMessage("Action non spécifiée !");
    }
    else {
        writeServiceMessage("Table incorrecte !");
    }

    //affichage de la page
    echo '<html>';
    echo getHead();

    echo '<body>
                        <main>';

    echo '<header>';
    echo getNav();
    if ($table == 'index') {
        echo getNavIndex();
    }
    if ($table == 'presentation') {
        echo getNavPresentation();
    }
    if ($table == 'works') {
        echo getNavWorks();
    }
    if ($table == 'services') {
        echo getNavServices();
    }
    if ($table == 'contact') {
        echo getNavContact();
    }
    echo '</header>';

    echo '<section>';
    $message = getServiceMessage();
    if ($message) {
        echo '<div class="alert alert-primary" role="alert">
                                                '.$message.'
                                        </div>';
    }
    echo $content;
    echo '</section>';

    echo getFooter();

    echo '<main>
                </body>
        </html>';

}
else{
    header('location: connected.php');
    exit();
}

?>