<?php

header('Location: areautente.php');
require "include/classes/UserPage.php";

$page = new UserPage();
echo $page->getPage();
