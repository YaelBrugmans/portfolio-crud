<?php

include 'db.php';

// démarrrage de la session de l'utilisateur
session_start();

// vérifie qu'un nom d'utilisateur a été posté ansi qu'un mot de passe, sinon, on affiche la table index et on sort de la session
if(isset($_POST['username']) && isset($_POST['password'])){
   $username = $_POST['username'];  
   $password = $_POST['password'];
   // comprend tout l'alphabet de A à Z avec et sans majuscules, pas de chiffres ou de caractères spéciaux autre que nécessaire
   $regexUsername = "^[a-zA-Z-\/'éèàù]+$^";
   // contient 12 caractères maxi et comprend tout l'alphabet de A à Z avec et sans majuscules, chiffres et caractères spéciaux autorisés
   $regexPassword = "^[a-zA-Z\-',?;.:\/+=%ù*$*¨_)(à!è§é&@#^]+$^";

   // on vérifie si le nom d'utilisateur et le mot de passe, sinon, on retourne à la page index
   if(preg_match($regexUsername, $username) && preg_match($regexPassword, $password)){
      $request = $db->prepare("SELECT * FROM `user` WHERE `username` = '$username'");
      $request->execute();
      // on retourne le nombre de lignes impliquées, sinon, on retourne à la page index
      if($request->rowCount() === 1) {
         // fetch(PDO::FETCH_ASSOC) retourne la ligne suivante en tant qu'un tableau indexé par le nom des colonnes
         $row = $request->fetch(PDO::FETCH_ASSOC);
         // dernière vérification avant de régler la session de l'utilisateur et de le renvoyer à la page index
         if(password_verify($password, $row['password']) === true) {
            // $row['username'] == $username &&
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            // redirection vers la page home
            header("location: connected.php?table=index&action=list");
            exit(); 
         }
         else{
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
      writeServiceMessage('');
      header('location: index.php?error');
      exit();
   }
}
else{
   header('location: index.php');
   exit();
}
   



