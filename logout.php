<?php 

//déconnection de la session
session_start();
session_unset();
session_destroy();

header('Location: /portfolio_crud/index.php');


?>
