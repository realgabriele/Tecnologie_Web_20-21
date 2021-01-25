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


$sql_update = "DELETE FROM utenti WHERE id=$utente_id";
if(mysqli_query($link, $sql_update)) {
    echo "<h1>Utente eliminato con successo </h1>";
    echo "<script> setTimeout(()=>window.location='index.php', 1000)</script>";
}