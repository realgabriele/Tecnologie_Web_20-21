<?php

   Class Auth_del {

      static function doLogin() {
         global  
            $skin, $mysqli;

         if (isset($_POST['referrer'])) {

            $result = $mysqli->query("SELECT * FROM users WHERE username = '{$_POST['username']}' AND password = md5('{$_POST['password']}')");
      
            if ($result->num_rows == 1) {
               $_SESSION['auth'] = $result->fetch_assoc();
            } else {
               Header("Location: error.php?loginError"); 
               exit;
            }
         } else {
      
            if (!isset($_SESSION['auth'])) {
               $skin = "skins/backoffice";
               $main = new Template("frame_public.html");
      
               $main->close();
               exit;
            }
         }      
      }
   }

   Auth::doLogin();
  
?>