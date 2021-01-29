<?php

require "ShowPage.php";

class ShowOrderPage extends ShowPage
{
    protected function single_page_body()
    {
        parent::single_page_body();
        $totale = 0;

        // show : articolo_ordine + articoli
        $query = "SELECT ao.*, foto, nome ".
            " FROM articolo_ordine AS ao ".
            " LEFT JOIN articoli ".
            " ON ao.articolo_id = articoli.id ".
            " WHERE ordine_id = :oid";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['oid' => $this->row_id]);

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $row["prezzo-totale"] = $row['prezzo'] * $row['quantita'];
            $totale += $row["prezzo-totale"];
            $this->body->setContent(array_key_append($row, "_articolo"), null);
        }

        $this->body->setContent("totale_ordine", $totale);
    }
}