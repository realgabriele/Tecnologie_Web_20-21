<?php

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

session_start();

$main = new Template("frame-public.html");
$body = new Template("recensioni/scrivi.html");

$id = $_GET["id"];

global $dbh;
if (!$result = $dbh->query("SELECT * FROM articoli WHERE id=$id")) {
    echo "Error: ", $dbh->errorInfo();
    exit(1);
}

$id_utente = $_SESSION['auth_uid'];

if($res = $dbh->query("SELECT * FROM recensioni WHERE articolo_id=$id AND utente_id=$id_utente")) {
    $content = $res->fetch(PDO::FETCH_ASSOC);
    if(isset($content['titolo']))
        $titolo = $content['titolo'];

    if(isset($content['descrizione']))
        $descrizione = $content['descrizione'];

    if(isset($content['rating']))
        $rating = $content['rating'];

    if(isset($titolo))
        $body->setContent("titolo", $titolo);

    if(isset($descrizione))
        $body->setContent("descrizione_rec", $descrizione);

    if(isset($rating))
        $body->setContent("rating", $rating);
}
for($i=0; $i<$result->rowCount(); $i++) {
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $body->setContent($data, null);
}

$recensioni = new Template("articoli/recensioni.html");

if (!$result = $dbh->query("SELECT * FROM recensioni WHERE articolo_id=$id")) {
    echo "Error: ", $dbh->errorInfo();
    exit(1);
}

for($i=0; $i<$result->rowCount(); $i++) {
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $recensioni->setContent($data, null);
}

$main->setContent("body", $body->get());
$main->close();

?>