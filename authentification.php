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

   // on vérifie si le nom d'utilisateur correspond à l'expression regulière de $regexUsername, sinon, on retourne à la page index
   if(preg_match($regexUsername, $username)){
      $request = $db->prepare("SELECT * FROM `portfolio_user` WHERE `username` = '$username'");
      $request->execute();
      // on retourne le nombre de lignes impliquées, sinon, on retourne à la page index avec l'affichage de l'erreur
      if($request->rowCount() === 1) {
         // fetch(PDO::FETCH_ASSOC) retourne la ligne suivante en tant qu'un tableau indexé par le nom des colonnes
         $dataLine = $request->fetch(PDO::FETCH_ASSOC);
         // dernière vérification avant de régler la session de l'utilisateur et de le renvoyer à la page index sans erreur
         if($dataLine['username'] === $username && $password === $dataLine['password']) {
//             if($dataLine['username'] === $username && password_verify($password, $dataLine['password']) === true) {
            $_SESSION['id'] = $dataLine['id'];
            $_SESSION['username'] = $dataLine['username'];
            // redirection vers la page index
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
      header('location: index.php?error');
      exit();
   }
}
else{
   header('location: index.php');
   exit();
}
   



