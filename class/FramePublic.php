<?php
session_start();

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";
require "include/cart.inc.php";

class FramePublic
{
    /* templates */
    public $main;
    public $body;
    public $cart_sidebar;
    public $utente;

    /* objects */
    public $cart;

    public function __construct()
    {
        $this->main = new Template("frame-public.html");
        $this->cart_sidebar = new Template('cart-sidebar.html');

        // load the Cart or create a new one
        /*if (isset($_SESSION['auth'])) {
            $cart = new Cart($_SESSION['auth']['id']);
        } else {
            $cart = new Cart();
        }*/ $this->cart = new Cart("1");

        $this->handleRequest();

        $this->updateHeader();
        $this->updateBody();
    }

    public function handleRequest(){
        // default none
    }

    public function updateBody(){
        // default none
    }

    public function updateHeader(){
        global $mysqli;
        // set every Item inside the Cart-Sidebar
        foreach ($this->cart->getItems() as $cart_item) {
            $result = $mysqli->query("SELECT * FROM articoli WHERE id={$cart_item['id']}");
            $db_data = $result->fetch_assoc();
            $data = array_merge(array_key_append($cart_item, "-carrello"), $db_data);
            $data["prezzo-totale"] = $data['prezzo'] * $data['quantita-carrello'];

            $this->cart_sidebar->setContent($data, null);

            $this->cart_sidebar->setContent("totale-carrello", $this->cart->getTotalPrice());
            $this->cart_sidebar->setContent("totale-quantita-carrello", $this->cart->getTotalQuantity());
            $this->main->setContent("totale-quantita-carrello", $this->cart->getTotalQuantity());
        }
    }

    public function getPage(){
        $this->main->setContent("body", $this->body->get());
        $this->main->setContent("cart-sidebar", $this->cart_sidebar->get());
        return $this->main->get();
    }
}