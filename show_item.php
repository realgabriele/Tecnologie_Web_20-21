<?php

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

$main = new Template("frame-public.html");
$body = new Template("articoli/show.html");

$id = $_GET["id"];

if (!$result = $mysqli->query("SELECT * FROM articoli WHERE id=$id")) {
    echo "Error: ", $mysqli->error;
}

for($i=0; $i<$result->num_rows; $i++) {
    $data = $result->fetch_assoc();
    $body->setContent($data, null);
}


$recensioni = new Template("articoli/recensioni.html");

if (!$result = $mysqli->query("SELECT * FROM recensioni WHERE articolo_id=$id")) {
    echo "Error: ", $mysqli->error;
}
for($i=0; $i<$result->num_rows; $i++) {
    $data = $result->fetch_assoc();
    $recensioni->setContent($data, null);
}


$body->setContent("recensioni", $recensioni->get());
$main->setContent("body", $body->get());
$main->close();

?>