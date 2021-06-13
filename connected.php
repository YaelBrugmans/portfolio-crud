<?php

include 'functions.php';
include 'db.php';

session_start();

// si un id et un nom d'utilisateur a été envoyé, la table complète avec le tableau s'affiche, sinon il est envoyé à une table d'erreur
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {

        // navigation entre les tables
        $table = '`index`';
        $action = 'list';

        
        
        if(isset($_GET['table'])){
                $table = $_GET['table'];
                if(isset($_GET['action'])){
                $action = $_GET['action'];
                }
        }
        $content='';

        // retourne la table demandée
        if (($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`') && $action == 'list'){
                 $content = displayTable($db, $table);
        }
        else if(($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`') && $action == 'create' ){
                $content = createdataLine($db, $table, $action);
        }
        else if (($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`') && $action == 'delete'){
                deletedataLine($db, $table);
        }
        else if (($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`') && $action == 'update'){
                $content = updatedataLine($db, $table, $action);
        }
        else if (($table == '`index`' || $table == '`presentation`' || $table == '`travaux`' || $table == '`services`' || $table == '`contact`') && !$action){
                writeServiceMessage("Action non spécifiée !");
        }
        else {
                writeServiceMessage("Table incorrecte !");
        }

        //affichage de la table
        echo '<html>';
                echo getHead();

                echo '<body>
                        <main>';

                                echo '<header>';
                                echo getNav();
                                        if ($table == '`index`') {
                                                echo getNavIndex();
                                        }
                                        if ($table == '`presentation`') {
                                                echo getNavPresentation();
                                        }
                                        if ($table == '`travaux`') {
                                                echo getNavTravaux();
                                        }
                                        if ($table == '`services`') {
                                                echo getNavServices();
                                        }
                                        if ($table == '`contact`') {
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

                                echo '
                                <footer>
                                    <p>Yael-Brugmans-<a class="nav-link" href="mailto:yaelbrugmans1998@gmail.com">yaelbrugmans1998@gmail.com</a>
                                    </p>
                                </footer>
                                ';

                        echo '<main>
                </body>
        </html>';

}
else{
        header('location: connected.php');
        exit();
}

?>