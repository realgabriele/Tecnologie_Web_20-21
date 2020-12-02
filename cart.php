<?php
require_once "include/config.inc.php";
require "class/CartPage.php";

$page = new CartPage();
echo $page->getPage();