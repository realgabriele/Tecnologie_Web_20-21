<?php
error_reporting(E_ALL);

require "include/classes/ShopPage.php";

$page = new ShopPage();
echo $page->getPage();
