<?php

require "FramePublic.php";

class LoginPage extends FramePublic
{
    private $error_msg;

    public function __construct()
    {
        $this->body = new Template("login.html");
        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        // se è gia autenticato, ridireziona verso un altra pagina
        if($this->auth->isAuthenticated){
            // ToDo: last page referer
            header("Location: index.php");
            die();
        }
    }

    public function handleRequest()
    {
        if ( isset($_POST['email']) && isset($_POST['password']) ) {
            $res = $this->auth->login($_POST['email'], $_POST['password']);
            if (!$res['error']) {
                // ToDo: last page referer
                header("Location: index.php");
                die();
            } else {
                $this->error_msg = "login error: " . $res['message'];
            }
        }
    }

    public function updateBody()
    {
        $this->body->setContent("error_msg", $this->error_msg);
    }
}