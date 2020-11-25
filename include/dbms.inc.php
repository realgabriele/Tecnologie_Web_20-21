<?php

   $mysqli = new mysqli("localhost", "web", "Pierantonio", "TdW2021");
   
   if($mysqli->connect_errno) {
      echo "Error: ", $mysqli->connect_error;
      exit;
   }

?>
