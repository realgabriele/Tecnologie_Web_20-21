<?php

require "DeletePage.php";

class DeleteWishlistPage extends DeletePage
{
    protected function deleteMany()
    {
        // remove old articolo_wishlist entries
        $query = "DELETE FROM `articolo_wishlist` WHERE wishlist_id = ?";
        $query_prepared = $this->dbh->prepare($query);
        $a = $query_prepared->execute([$this->row_id]);

        print_r($query_prepared->errorInfo());
    }
}