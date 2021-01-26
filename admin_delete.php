<?php

require "include/classes/administrator/DeleteBackPage.php";

$page = new DeleteBackPage($_GET['table'] ?? null, $_GET['id'] ?? null);
echo $page->getPage();

