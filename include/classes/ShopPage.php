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
        global $mysqli;
        if (!$result = $mysqli->query("SELECT * FROM articoli")) {
            echo "Error: ", $mysqli->error;
        }

        for($i=0; $i<$result->num_rows; $i++) {
            $data = $result->fetch_assoc();
            $this->body->setContent($data, null);
        }
    }
}
