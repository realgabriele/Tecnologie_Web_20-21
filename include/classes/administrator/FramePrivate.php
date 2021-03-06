<?php
session_start();

/* global template2 variables initialization */
global $skin;
$skin = "skins/back";
$GLOBALS['config']['skin'] = $skin;
$GLOBALS['config']['base'] = "$skin";

/* general */
require "include/template2.inc.php";
require "include/dbms.inc.php";
require "include/utility.inc.php";
/* user authentication */
require 'include/classes/PHPAuth/Auth.php';
require 'include/classes/PHPAuth/Config.php';
/* cart */
require "include/classes/Cart.php";

class FramePrivate
{
    /* templates */
    public $main;
    public $body;

    /* objects */
    public $auth;
    public $dbh;

    public function __construct()
    {
        //$this->main = new Template("frame-private.html");
        $this->main = new Template("frame-private.html");
        if(!isset($this->body)) $this->body = new Template("void");

        /* load Authentication / Authorization class */
        global $dbh;
        $this->dbh = $dbh;
        $this->auth = new PHPAuth\Auth();

        $this->check_authorization();

        $this->handleRequest();

        $this->updateHeader();
        $this->updateBody();
    }

    public function check_authorization($actions = []){
        $actions = array_merge($actions, ['backoffice']);
        foreach ($actions as $action){
            $id = $this->auth->isAuthenticated ? $this->auth->getCurrentUID() : 0;
            if (!$this->auth->is_authorized($id, $action)) {
                $this->render_error("Operazione non autorizzata: ". $action);
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
        $user_data = $this->auth->getCurrentUser(true);
        $this->main->setContent($user_data, null);

        /* set left sidebar menu */
        // todo: migliorare
        $tables = ["utenti", "gruppi", "servizi", "articoli", "categorie", "recensioni", "ordini"];
        foreach ($tables as $table_name){
            $this->main->setContent("table_name", $table_name);
        }
    }

    public function getPage(){
        $this->main->setContent("body", $this->body->get());
        return $this->main->get();
    }

    protected function render_error($message = ""){
        if (!$this->auth->isAuthenticated) {
            // show login page
            $this->main = new Template("login.html");
            $this->main->setContent("error_message", $message .
                "<br>Se sei autorizzato effettua il login");
        } else {
            $this->body = new Template("error.html");
            $this->body->setContent("error_message", $message);
        }
        echo $this->getPage();
        exit();
    }

}