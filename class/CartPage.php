<?php

require "FramePublic.php";
require "include/utility.inc.php";

class CartPage extends FramePublic
{

    public function __construct()
    {
        $this->body = new Template("cart.html");
        parent::__construct();
    }

    public function handleRequest()
    {
        if (isset($_POST['add'])){
            $this->cart->add($_POST['add']);
        }
        if (isset($_POST['sub'])){
            $this->cart->sub($_POST['sub']);
        }
    }

    public function updateBody()
    {
        global $mysqli;
        // set all the single Items inside the Cart
        foreach ($this->cart->getItems() as $cart_item) {
            $result = $mysqli->query("SELECT * FROM articoli WHERE id={$cart_item['id']}");
            $db_data = $result->fetch_assoc();
            $data = array_merge(array_key_append($cart_item, "-carrello"), $db_data);
            $data["prezzo-totale"] = $data['prezzo'] * $data['quantita-carrello'];

            $this->body->setContent($data, null);
        }

        $this->body->setContent("totale-carrello", $this->cart->getTotalPrice());
    }
}