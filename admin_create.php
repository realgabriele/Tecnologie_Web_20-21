<?php

require "include/classes/administrator/CreateBackPage.php";

$page = new CreateBackPage($_GET['table'] ?? null);
echo $page->getPage();

