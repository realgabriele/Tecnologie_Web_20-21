<?php

require "FramePublic.php";

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
            $this->cart->add($_POST['add'], $_POST['quantity'] ?? 1);
        }
        if (isset($_POST['sub'])){
            $this->cart->sub($_POST['sub']);
        }
        if (isset($_POST['del'])){
            $this->cart->remove($_POST['del']);
        }
        if (isset($_POST['set_item']) && isset($_POST['set_quantity'])){
            $this->cart->update($_POST['set_item'], $_POST['set_quantity']);
        }
    }

    public function updateBody()
    {
        // set all the single Items inside the Cart
        foreach ($this->cart->getItems() as $cart_item) {
            $result = $this->dbh->query("SELECT * FROM articoli WHERE id={$cart_item['id']}");
            $db_data = $result->fetch(PDO::FETCH_ASSOC);
            $data = array_merge(array_key_append($cart_item, "-carrello"), $db_data);
            $data["prezzo-totale"] = $data['prezzo'] * $data['quantita-carrello'];

            $this->body->setContent($data, null);
        }

        $this->body->setContent("totale-carrello", $this->cart->getTotalPrice());
    }
}