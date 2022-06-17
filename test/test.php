include 'db.php';

// vérifie qu'un nom d'utilisateur a été posté ansi qu'un mot de passe, sinon, on affiche la table index et on sort de la session
if(isset($_POST['username']) && isset($_POST['password'])){
   $username = $_POST['username'];
   $password = $_POST['password'];
   // comprend tout l'alphabet de A à Z avec et sans majuscules, pas de chiffres ou de caractères spéciaux autre que nécessaire
   $regexUsername = "^[a-zA-Z-\/'éèàù]+$^";
   // on vérifie si le nom d'utilisateur correspond à l'expression regulière de $regexUsername, sinon, on retourne à la page index
   if(preg_match($regexUsername, $username)){
      var_dump("enter 2");
      $request = $db->prepare("SELECT * FROM `portfolio_user` WHERE `username`=:username");
      $request->execute();
      var_dump($request);
      // on retourne le nombre de lignes impliquées, sinon, on retourne à la page index avec l'affichage de l'erreur
      if($request->rowCount() === 1) {
         var_dump('enter 3');
         // fetch(PDO::FETCH_ASSOC) retourne la ligne suivante en tant qu'un tableau indexé par le nom des colonnes
         $dataLine = $request->fetch(PDO::FETCH_ASSOC);
         var_dump($dataLine);
         // dernière vérification avant de régler la session de l'utilisateur et de le renvoyer à la page index sans erreur
         if($dataLine['username'] === $username && md5($password) === $dataLine['password']) {
            var_dump("enter username");
            if (isset($dataLine['id']) && isset($dataLine['username'])) {
               var_dump("enter dataline");
               $_SESSION['id'] = $dataLine['id'];
               $_SESSION['username'] = $dataLine['username'];
               // redirection vers la page index
               header("location: connected.php?table=index&action=list");
               exit();
            }
            // header("location: index.php?error");
               exit();
         }
         else{
            header('location: index.php?error');
            exit();
         }
      }
      else{
         // header('location: index.php?error');
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




