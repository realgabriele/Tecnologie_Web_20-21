<?php

require "include/classes/administrator/DeleteBackPage.php";

if ($_GET['table'] == "articolo_ordine") {
    require "include/classes/administrator/DeleteAOBackPage.php";
    $page = new DeleteAOBackPage($_GET['table'] ?? null, $_GET['id'] ?? null);
} else {
    $page = new DeleteBackPage($_GET['table'] ?? null, $_GET['id'] ?? null);
}

echo $page->getPage();

