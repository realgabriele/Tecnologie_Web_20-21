<?php

require "ShowPage.php";

class ShowWishlistPage extends ShowPage
{
    protected function single_page_body()
    {
        parent::single_page_body();

        // show : articolo_ordine + articoli
        $query = "SELECT aw.*, foto, nome, prezzo ".
            " FROM articolo_wishlist AS aw ".
            " LEFT JOIN articoli ".
            " ON aw.articolo_id = articoli.id ".
            " WHERE wishlist_id = :wid";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['wid' => $this->row_id]);

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $row["prezzo-totale"] = $row['prezzo'] * $row['quantita'];
            $this->body->setContent(array_key_append($row, "_articolo"), null);
        }

    }
}