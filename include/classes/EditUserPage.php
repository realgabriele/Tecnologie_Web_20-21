<?php

require "FramePublic.php";

class EditUserPage extends FramePublic
{
    public function __construct()
    {
        $this->body = new Template("utenti/edit.html");
        parent::__construct();
    }

    public function handleRequest()
    {
        $params = $_POST;

        if (isset($params['email'])) {
            $res = $this->auth->changeEmail($this->auth->getCurrentUID(),
                $params['email'], $params['old_password']);
            if ($res['error']) {
                $this->error_msg = "Error: " . $res['message'];
                return;
            }
            unset($params['email']);
        }

        if (isset($params['new_password'])) {
            $res = $this->auth->changePassword($this->auth->getCurrentUID(),
                $params['old_password'], $params['new_password'], $params['repeat_password']);
            if ($res['error']) {
                $this->error_msg = "Error: " . $res['message'];
                return;
            }
            unset($params['new_password']);
            unset($params['repeat_password']);
        }

        unset($params['old_password']);

        $this->auth->updateUser($this->auth->getCurrentUID(), $params);
    }

}