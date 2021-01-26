<?php

require "FramePrivate.php";

class AdminBackPage extends FramePrivate
{
    public function __construct()
    {
        $this->body = new Template("dashboard.html");
        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        parent::check_authorization($actions);
    }

    public function updateBody()
    {
    }
}