<?php
require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";


session_start();


$link = mysqli_connect("localhost", "root", "", "tdw2021");


// Connessione al db
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Riprendo Campi HTML

$utente_id=$_SESSION['auth_uid'];


$nome=$_REQUEST['nome'];
$email=$_REQUEST['email'];
$password=password_hash($_REQUEST['password'], PASSWORD_BCRYPT);

$sql_update = "UPDATE utenti SET nome='$nome', email='$email', password='$password' WHERE id=$utente_id";
if(mysqli_query($link, $sql_update)) {
    echo "Utente aggiornato con successo!";
    header('Location: areautente.php');
}


echo "Fatal error, impossibile modificare l'utente" . mysqli_error($link);