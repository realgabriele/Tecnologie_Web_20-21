<?php

require "FramePublic.php";

class LoginPage extends FramePublic
{
    protected $referer;
    protected $error_message;

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
                $this->error_message = "login error: " . $res['message'];
            }
        }
    }

    public function updateBody()
    {
        $this->body->setContent("error_message", $this->error_message);

        $this->body->setContent("referer", $this->referer);
        $this->body->setContent("email_old", $_POST['email']);
    }
}