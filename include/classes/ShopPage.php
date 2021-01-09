<?php

require "FramePublic.php";

class ShopPage extends FramePublic
{
    protected $query_cond;

    public function __construct()
    {
        $this->body = new Template("shop.html");
        $this->query_cond = "";
        parent::__construct();
    }

    public function handleRequest()
    {
        if (isset($_GET['q'])) {
            $a = "";
        }
    }

    public function updateBody()
    {
        $this->main->setContent("q_old", $_GET['q'] ?? "");

        if (!$result = $this->dbh->query("SELECT * FROM articoli")) {
            echo "Error: ";
            print_r($this->dbh->errorInfo());
        }

        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $row) {
            $this->body->setContent($row, null);
        }
    }
}
