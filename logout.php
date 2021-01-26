<?php
require "include/classes/FramePublic.php";

$page = new FramePublic();
$page->auth->logout();

header('Location: ' . $_SERVER['HTTP_REFERER']);
