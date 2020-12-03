<?php

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

$main = new Template("frame-public.html");
$body = new Template("show_item.html");

$id = $_GET["id"];

// retrieve dal DB

$main->setContent("body", $body->get());

$main->close();

?>