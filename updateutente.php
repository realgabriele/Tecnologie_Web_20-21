<?php
require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";


session_start();




global $dbh;

// Riprendo Campi HTML

$utente_id=$_SESSION['auth_uid'];


$nome=$_REQUEST['nome'];
$email=$_REQUEST['email'];
$password=password_hash($_REQUEST['password'], PASSWORD_BCRYPT);

if ($sql_update = $dbh->query( "UPDATE utenti SET nome='$nome', email='$email', password='$password' WHERE id=$utente_id")){
//if(mysqli_query($link, $sql_update)) {
    echo "Utente aggiornato con successo!";
    header('Location: areautente.php');
}


echo "Fatal error, impossibile modificare l'utente" . mysqli_error($link);