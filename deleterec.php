<?php

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

session_start();


$articolo_id = $_GET["articolo_id"];
$utente_id = $_SESSION["auth_uid"];

global $dbh;

$sql_delete = "DELETE FROM recensioni WHERE articolo_id='$articolo_id' AND utente_id='$utente_id'";
$dbh->query($sql_delete);
header ('Location:show_item.php?id=' . $articolo_id);

?>