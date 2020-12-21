<?php
require "include/classes/FramePublic.php";

$page = new FramePublic();
$page->auth->logout($page->auth->getCurrentSessionHash());

header('Location: ' . $_SERVER['HTTP_REFERER']);
