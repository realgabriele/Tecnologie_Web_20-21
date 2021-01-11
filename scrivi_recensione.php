<?php

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

$main = new Template("frame-public.html");
$body = new Template("recensioni/scrivi.html");

$id = $_GET["id"];

global $dbh;
if (!$result = $dbh->query("SELECT * FROM articoli WHERE id=$id")) {
    echo "Error: ", $result->errorInfo();
}

for($i=0; $i<$result->rowCount(); $i++) {
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $body->setContent($data, null);
}


$recensioni = new Template("articoli/recensioni.html");

if (!$result = $dbh->query("SELECT * FROM recensioni WHERE articolo_id=$id")) {
    echo "Error: ", $result->errorInfo();
}
for($i=0; $i<$result->rowCount(); $i++) {
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $recensioni->setContent($data, null);
}


$body->setContent("recensioni", $recensioni->get());
$main->setContent("body", $body->get());
$main->close();

?>