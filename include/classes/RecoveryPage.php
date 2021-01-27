<?php

require "FramePublic.php";

class RecoveryPage extends FramePublic
{
    protected $recovery_key;

    public function __construct()
    {
        $this->recovery_key = $_GET['key'] ?? null;
        if ($this->recovery_key) {
            $this->body = new Template("recovery_password.html");
        } else {
            $this->body = new Template("recovery_request.html");
        }
        parent::__construct();
    }

    public function handleRequest()
    {
        if (isset($_POST['email'])) {
            $res = $this->auth->requestReset($_POST['email']);
            if (!$res['error']) {
                $this->body->setContent("success_msg", $res['message']);
            } else {
                print_r($res);
            }
        }

        if (isset($_GET['key']) && isset($_POST['password'])){
            $res = $this->auth->resetPass($this->recovery_key, $_POST['password'], $_POST['password_repeat']??null);
            if (!$res['error']) {
                $this->body->setContent("success_msg", $res['message']);
            } else {
                print_r($res);
            }
        }
    }
}