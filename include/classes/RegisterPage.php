<?php

require "FramePublic.php";

class RegisterPage extends FramePublic
{
    protected $error_msg, $success_msg;

    public function __construct()
    {
        $this->body = new Template("register.html");
        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        // se è gia autenticato, ridireziona verso un'altra pagina
        if($this->auth->isAuthenticated){
            // ToDo: last page referer
            header("Location: index.php");
            die();
        }
    }

    public function handleRequest()
    {
        if (!isset($_POST['email'])){
            $this->error_msg = "il campo EMAIL deve essere specificato";
            return;
        }
        if (!isset($_POST['password'])){
            $this->error_msg = "il campo PASSWORD deve essere specificato";
            return;
        }

        $params = [];
        if (isset($_POST['nome'])) $params['nome'] = $_POST['nome'];

        $res = $this->auth->register($_POST['email'], $_POST['password'], $_POST['repeat_password'], $params);

        if ($res['error']) {
            $this->error_msg = "registration error: " . $res['message'];
        } else {
            $this->success_msg = "registrazione completata!";
        }
    }

    public function updateBody()
    {
        $this->body->setContent("success_msg", $this->success_msg);
        $this->body->setContent("error_msg", $this->error_msg);

        $this->body->setContent("email_old", $_POST['email']);
    }
}