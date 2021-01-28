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
        $query = "SELECT  (
            SELECT COUNT(*)
            FROM ordini
            ) AS total_orders,
            (
            SELECT COUNT(*)
            FROM   utenti
            ) AS total_users
           ";
        $res = $this->dbh->query($query);

        $this->body->setContent($res->fetchAll(PDO::FETCH_ASSOC)[0], null);
    }
}