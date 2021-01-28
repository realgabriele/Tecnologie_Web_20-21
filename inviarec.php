<?php
require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

$main = new Template("frame-public.html");
$body = new Template("articoli/show_single.html");
session_start();

global $dbh;


// Riprendo Campi HTML
$utente_id=$_SESSION['auth_uid'];
$articolo_id = $_GET['id'];
$titolo=($_POST['titolo']);
$descrizione = ($_POST['descrizione']);
$rating = ($_POST['rating']);

// QUERY
$sql = "INSERT INTO recensioni (utente_id, articolo_id, titolo,descrizione,rating) 
VALUES ('$utente_id', '$articolo_id','$titolo','$descrizione','$rating')";

$sql_update = "UPDATE recensioni SET titolo='$titolo', descrizione='$descrizione', rating=$rating
 WHERE articolo_id=$articolo_id AND utente_id=$utente_id";



if($sql2=$dbh->query($sql)) {
    echo "RECENSIONE AGGIUNTA";
    header('Location: show_item.php?id=' .  $articolo_id . '&created=Recensione inserita con successo' );
} else {
    if($sql_update2=$dbh->query($sql_update)) {
        header ('Location: show_item.php?id=' . $articolo_id . '&created=Recensione aggiornata con successo');
    } else {
        echo "ERROR: HAI GIA' INSERITO LA QUERY $sql. " . mysqli_error($link);
        header('Location: show_item.php?id=' . $articolo_id . '&rejected=Recensione già esistente');
    }
}
