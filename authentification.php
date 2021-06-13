<?php

include 'db.php';

// démarrrage de la session de l'utilisateur
session_start();

// vérifie qu'un nom d'utilisateur a été posté ansi qu'un mot de passe, sinon, on affiche la table index et on sort de la session
if(isset($_POST['username']) && isset($_POST['password'])){
   $username = $_POST['username'];  
   $password = $_POST['password'];
   // comprend tout l'alphabet de A à Z avec et sans majuscules
   $regexUsername = "^[a-zA-Z]+$^";

   // on vérifie si le nom d'utilisateur correspond à l'expression regulière de $regexUsername, sinon, on retourne à la page index
   if(preg_match($regexUsername, $username)){
      $request = $db->prepare("SELECT * FROM `user` WHERE `username` = '$username'");
      $request->execute();
      // on retourne le nombre de lignes impliquées, sinon, on retourne à la page index
      if(preg_match($regexUsername, $username)) {
         $dataLine = $request->fetch(PDO::FETCH_ASSOC);
         // dernière vérification avant de régler la session de l'utilisateur et de le renvoyer à la page index
         if($dataLine['username'] === $username && $dataLine['password'] === $username) {
            $_SESSION['id'] = $dataLine['id'];
            $_SESSION['username'] = $dataLine['username'];
            // redirection vers la page index
            header("location: connected.php?table=`index`&action=list");
            exit(); 
         }
         else{
            header('location: index.php?error');
            exit();
         }
      }
      else{
      writeServiceMessage("");

         header('location: index.php?error');
         exit();
      }     
   }
   else{
      header('location: index.php?error');
      exit();
   }
}
else{
   header('location: index.php');
   exit();
}
   



