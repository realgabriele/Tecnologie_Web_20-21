
<?php

error_reporting(E_ALL);

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

$main = new Template("frame-public.html");
$body = new Template("index.html");

if (!$result = $mysqli->query("SELECT * FROM articoli")) {
    echo "Error: ", $mysqli->error;
}

for($i=0; $i<$result->num_rows; $i++) {
    $data = $result->fetch_assoc();
    $body->setContent($data, null);
}

$main->setContent("body", $body->get());

$main->close();

?>