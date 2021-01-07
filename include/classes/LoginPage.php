<?php

require "FramePublic.php";

class LoginPage extends FramePublic
{
    protected $referer;
    protected $error_msg;

    public function __construct()
    {
        $this->body = new Template("login.html");
        $this->referer = $_SERVER['HTTP_REFERER'] ?? "index.php";
        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        // se è gia autenticato, ridireziona verso un'altra pagina
        if($this->auth->isAuthenticated){
            header("Location: " . $this->referer);
            die();
        }
    }

    public function handleRequest()
    {
        if (isset($_POST['referer'])) $this->referer = $_POST['referer'];

        if ( isset($_POST['email']) && isset($_POST['password']) ) {
            $res = $this->auth->login($_POST['email'], $_POST['password']);
            if (!$res['error']) {
                header("Location: " . $this->referer);
                echo $this->referer;
                die();
            } else {
                $this->error_msg = "login error: " . $res['message'];
            }
        }
    }

    public function updateBody()
    {
        $this->body->setContent("error_msg", $this->error_msg);

        $this->body->setContent("referer", $this->referer);
        $this->body->setContent("email_old", $_POST['email'] ?? "");
    }
}