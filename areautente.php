<?php

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

$main = new Template("frame-public.html");
$body = new Template("areautente.html");

$id = 1 ;

global $dbh;
if (!$result = $dbh->query("SELECT * FROM articoli WHERE id=$id")) {
    echo "Error: ", $mysqli->error;
}

for($i=0; $i<$result->rowCount(); $i++) {
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $body->setContent($data, null);
}

$main->setContent("body", $body->get());
$main->close();
