<?php

session_start();

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";
require "include/cart.inc.php";
require "include/utility.inc.php";

$main = new Template("frame-public.html");
$body = new Template("cart.html");

// load the Cart or create a new one
if (isset($_SESSION['auth'])) {
    $cart = new Cart($_SESSION['auth']['id']);
} else {
    $cart = new Cart();
}

// set all the single Items inside the Cart
foreach ($cart->getItems() as $cart_item) {
    $result = $mysqli->query("SELECT * FROM articoli WHERE id={$cart_item['id']}");
    $db_data = $result->fetch_assoc();
    $data = array_merge(array_key_append($cart_item, "-carrello"), $db_data);

    $data["prezzo-totale"] = $data['prezzo'] * $data['quantita-carrello'];
    $body->setContent($data, null);
}

$body->setContent("totale-carrello", $cart->getTotalPrice());

$main->setContent("body", $body->get());

$main->close();

?>