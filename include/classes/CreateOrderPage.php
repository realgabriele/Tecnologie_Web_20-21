<?php

require "CreatePage.php";

class CreateOrderPage  extends CreatePage
{
    protected function handle_new_id()
    {
        /**
         * transforms the cart in a placed order
         * warning: this action cannot be undone
         */

        // copy from articolo_carrello to articolo_ordine
        $query = "INSERT INTO `articolo_ordine` ".
            " (ordine_id, articolo_id, quantita, prezzo) ".
            " SELECT :ordine_id, `articoli`.id, ac.quantita, `articoli`.prezzo ".
            " FROM `articolo_carrello` AS ac ".
            " JOIN `articoli` ON ac.articolo_id = `articoli`.id ".
            " WHERE carrello_id = :carrello_id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['ordine_id' => $this->new_row_id, 'carrello_id' => $this->cart->getCartId()]);

        // remove old articolo_carrello entries
        $query = "DELETE FROM `articolo_carrello` WHERE carrello_id = ?";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute([$this->cart->getCartId()]);

        header("location: show_order.php?table={$this->table_name}&id={$this->new_row_id}");
        die();
    }
}