<?php

require "include/classes/administrator/EditBackPage.php";

$page = new EditBackPage($_GET['table'] ?? null, $_GET['id'] ?? null);
echo $page->getPage();
