<?php
require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

$main = new Template("frame-public.html");
$body = new Template("articoli/show_single.html");
session_start();


$link = mysqli_connect("localhost", "root", "", "tdw2021");


// Connessione al db
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Riprendo Campi HTML

$utente_id=$_SESSION['auth_id'];
$articolo_id = $_GET['id'];
$titolo = mysqli_real_escape_string($link, $_REQUEST['titolo']);
$descrizione = mysqli_real_escape_string($link, $_REQUEST['descrizione']);
$rating = mysqli_real_escape_string($link, $_REQUEST['rating']);

// QUERY
$sql = "INSERT INTO recensioni (utente_id, articolo_id, titolo,descrizione,rating) 
VALUES ('$utente_id', '$articolo_id','$titolo','$descrizione','$rating')";

if(mysqli_query($link, $sql)) {
    echo "RECENSIONE AGGIUNTA";
    header('Location: show_item.php?id=' .  $articolo_id . '&created=Recensione inserita con successo' );
} else {
    echo "ERROR: HAI GIA' INSERITO LA QUERY $sql. " . mysqli_error($link);
    header('Location: show_item.php?id=' .  $articolo_id . '&rejected=Recensione già esistente');
}


?>