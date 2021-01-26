<?php

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

$main = new Template("frame-public.html");
$body = new Template("articoli/show_single.html");

$id = $_GET["id"];
if(isset($_GET['created']))
    $created = $_GET["created"];

if(isset($_GET['rejected']))
    $rejected = $_GET["rejected"];

global $dbh;
if (!$result = $dbh->query("SELECT `articoli`.*,AVG(rating) AS rating ".
    " FROM articoli LEFT JOIN `recensioni` ON `articoli`.id = `recensioni`.articolo_id ".
    " WHERE `articoli`.id=$id")) {
    echo "Error: "; print_r($dbh->errorInfo());
}

for($i=0; $i<$result->rowCount(); $i++) {
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $body->setContent($data, null);
}

$recensioni = new Template("articoli/recensioni.html");
if (!$result = $dbh->query("SELECT * FROM recensioni WHERE articolo_id=$id")) {
    echo "Error: ", $result->errorInfo();
}
if ($result->rowCount() == 0) {
    $recensioni->setContent("titolo", "Nessuna recensione");
    $recensioni->setContent("descrizione", "clicca sul pulsante per aggiungere la prima recensione");
}

for($i=0; $i<$result->rowCount(); $i++) {
    $data = $result->fetch(PDO::FETCH_ASSOC);
    if(isset($created))
        $recensioni->setContent("created", $created);
    if(isset($rejected))
        $recensioni->setContent("rejected", $rejected );
    $recensioni->setContent($data, null);
}

$body->setContent("recensioni", $recensioni->get());
$main->setContent("body", $body->get());
$main->close();
