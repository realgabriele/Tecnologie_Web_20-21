<?php

require "DeletePage.php";

class DeleteWishlistPage extends DeletePage
{
    protected function deleteMany()
    {
        // remove old articolo_wishlist entries
        $query = "DELETE FROM `articolo_wishlist` WHERE wishlist_id = ?";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute([$this->row_id]);

        // remove old wishlist_condivisione entries
        $query = "DELETE FROM `wishlist_condivisione` WHERE wishlist_id = ?";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute([$this->row_id]);
    }
}