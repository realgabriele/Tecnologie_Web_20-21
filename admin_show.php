<?php

require "include/classes/administrator/ShowBackPage.php";

$page = new ShowBackPage($_GET['table'] ?? null, $_GET['id'] ?? null);
echo $page->getPage();

