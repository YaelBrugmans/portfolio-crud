<?php 

//déconnection de la session
session_start();
session_unset();
session_destroy();

header('Location: index.php');


?>