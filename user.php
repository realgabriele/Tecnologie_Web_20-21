<?php

require "include/classes/FramePublic.php";
$page = new FramePublic();

$body = "";
$body .= "<a href='show.php?table=indirizzi'>Indirizzi</a><br/>";
$body .= "<a href='show.php?table=metodipagamento'>Metodi di Pagamento</a><br/>";
$body .= "<a href='show.php?table=ordini'>Ordini</a><br/>";
$body .= "<a href='show_wishlist.php?table=wishlist'>Wishlist</a><br/>";

$page->main->setContent("body", $body);

echo $page->getPage();
