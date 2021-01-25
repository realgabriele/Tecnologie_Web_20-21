<?php
// handle AJAX request for adding/removing Items from Cart

require "include/classes/CartPage.php";

$page = new CartPage();

header('Content-Type: application/json');
$array = array(
    "status" => "success",
    "cart_sidebar" => $page->cart_sidebar->get(),
    "cart_body" => $page->body->get(),
    "cart_badge" => $page->cart->getTotalQuantity(),
);
echo json_encode($array);
exit();
