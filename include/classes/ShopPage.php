<?php

require "FramePublic.php";

class ShopPage extends FramePublic
{
    public function __construct()
    {
        $this->body = new Template("shop.html");
        parent::__construct();
    }

    public function updateBody()
    {
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
