<?php

require "include/classes/administrator/CreateBackPage.php";

if ($_GET['table']=='articolo_ordine'){
    require "include/classes/administrator/CreateAOBackPage.php";
    $page = new CreateAOBackPage($_GET['table'] ?? null, $_GET['order_id'] ?? null);
} else {
    $page = new CreateBackPage($_GET['table'] ?? null);
}
echo $page->getPage();
