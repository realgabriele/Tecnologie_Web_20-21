<?php

require "FramePublic.php";

class UserPage extends FramePublic
{
    public function __construct()
    {
        $this->body = new Template("utenti/show.html");
        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        parent::check_authorization(array_merge($actions, ['utenti.show']));
    }

    public function updateBody()
    {
        $uid = $this->auth->getCurrentUID();


    }
}