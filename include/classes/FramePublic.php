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

        /* load Authentication / Authorization class */
        global $dbh;
        $this->dbh = $dbh;
        $this->auth = new PHPAuth\Auth($dbh, new PHPAuth\Config($dbh));

        /* load the Cart or create a new one */
        if ($this->auth->isAuthenticated) {
            $this->cart = new Cart($this->auth->getCurrentUID());
        } else {
            $this->cart = new Cart(3);
        }

        $this->check_authorization();

        $this->handleRequest();

        $this->updateHeader();
        $this->updateBody();
    }

    public function check_authorization($actions = []){
        foreach ($actions as $action){
            $id = $this->auth->isAuthenticated ? $this->auth->getCurrentUID() : 0;
            if (!$this->auth->is_authorized($id, $action)) {
                $this->render_error("Operazione non autorizzata su questa tabella");
            }
        }
    }

    public function handleRequest(){
        // default none
    }

    public function updateBody(){
        // default none
    }

    public function updateHeader(){
        /* set user information */
        if ($this->auth->isAuthenticated){
            $user_data = $this->auth->getCurrentUser(true);
        } else {
            $user_data = ['not_authenticated' => 'true'];
        }
        $this->main->setContent($user_data, null);


        /* set every Item inside the Cart-Sidebar */
        foreach ($this->cart->getItems() as $cart_item) {
            $result = $this->dbh->query("SELECT * FROM articoli WHERE id={$cart_item['id']}");
            $db_data = $result->fetch(PDO::FETCH_ASSOC);
            $data = array_merge(array_key_append($cart_item, "-carrello"), $db_data);
            $data["prezzo-totale"] = $data['prezzo'] * $data['quantita-carrello'];

            $this->cart_sidebar->setContent($data, null);
        }
        $this->cart_sidebar->setContent("totale-carrello", $this->cart->getTotalPrice());
        $this->cart_sidebar->setContent("totale-quantita-carrello", $this->cart->getTotalQuantity());
        $this->main->setContent("totale-quantita-carrello", $this->cart->getTotalQuantity());

    }

    public function getPage(){
        $this->main->setContent("body", $this->body->get());
        $this->main->setContent("cart-sidebar", $this->cart_sidebar->get());
        return $this->main->get();
    }

    protected function render_error($message = ""){
        if (!$this->auth->isAuthenticated) {
            // show login page
            $this->body = new Template("login.html");
            $this->body->setContent("error_message", $message .
            "<br>Se sei autorizzato effettua il login");
        } else {
            $this->body = new Template("error.html");
            $this->body->setContent("error_message", $message);
        }
        $this->updateHeader();
        echo $this->getPage();
        exit();
    }
}