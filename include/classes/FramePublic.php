<?php
session_start();

/* general */
require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";
require "include/utility.inc.php";
/* user authentication */
require 'include/classes/PHPAuth/Auth.php';
require 'include/classes/PHPAuth/Config.php';
/* cart */
require "include/classes/Cart.php";

class FramePublic
{
    /* templates */
    public $main;
    public $body;
    public $cart_sidebar;

    /* objects */
    public $auth;
    public $cart;

    public function __construct()
    {
        $this->main = new Template("frame-public.html");
        $this->cart_sidebar = new Template('cart-sidebar.html');
        if(!isset($this->body)) $this->body = new Template("void");

        /* load the Cart or create a new one */
        /*if (isset($_SESSION['auth'])) {
            $cart = new Cart($_SESSION['auth']['id']);
        } else {
            $cart = new Cart();
        }*/ $this->cart = new Cart("1");

        /* load Authentication / Authorization class */
        $dbh = new PDO("mysql:dbname=tdw2021;host=localhost", "web", "Pierantonio");
        $this->auth = new PHPAuth\Auth($dbh, new PHPAuth\Config($dbh));

        $this->check_authorization();

        $this->handleRequest();

        $this->updateHeader();
        $this->updateBody();
    }

    public function check_authorization($actions = []){
        foreach ($actions as $action){
            $id = $this->auth->isAuthenticated ? $this->auth->getCurrentUID() : 0;
            if (!$this->auth->is_authorized($id, $action)) {
                $this->render_error("Not Authorized!");
                return false;
            }
        }
        return true;
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

    private function render_error($message = ""){
        if (!$this->auth->isAuthenticated) {
            $this->body = new Template("login.html");
        } else {
            $this->body = new Template("error.html");
            $this->body->setContent("error_message", $message);
        }
    }
}