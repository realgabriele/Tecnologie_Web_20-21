<?php

require "include/classes/administrator/ShowBackPage.php";

$page = new ShowBackPage($_GET['table'] ?? null, $_GET['id'] ?? null);
// $page->body = new Template("utenti/show_multiple.html");
echo $page->getPage();

