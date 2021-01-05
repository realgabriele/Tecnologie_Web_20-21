<?php

require "FramePublic.php";

class CheckoutPage extends FramePublic
{
    public function __construct()
    {
        $this->body = new Template("checkout/checkout.html");
        parent::__construct();
    }

    public function updateBody()
    {
        // show all cart items
        $this->setCarrello();

        // show all address
        $this->setIndirizzi();

        // show all payment method
        $this->setPagamenti();
    }

    protected function setCarrello()
    {
        $carrello = new Template("checkout/carrello.html");

        foreach ($this->cart->getItems() as $cart_item) {
            $result = $this->dbh->query("SELECT * FROM articoli WHERE id={$cart_item['id']}");
            $db_data = $result->fetch(PDO::FETCH_ASSOC);
            $data = array_merge(array_key_append($cart_item, "-carrello"), $db_data);
            $data["prezzo-totale"] = $data['prezzo'] * $data['quantita-carrello'];

            $carrello->setContent($data, null);
        }
        $carrello->setContent("totale-carrello", $this->cart->getTotalPrice());

        $this->body->setContent("carrello", $carrello->get());
    }

    protected function setIndirizzi()
    {
        $indirizzi = new Template("checkout/indirizzi.html");

        $query = "SELECT `indirizzi`.* FROM `utenti` ".
            " JOIN `indirizzi` ON `utenti`.id = `indirizzi`.utente_id ".
            " WHERE `utenti`.id = :uid";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['uid' => $this->auth->getCurrentUID()]);

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $indirizzi->setContent($row, null);
        }

        $this->body->setContent("indirizzi", $indirizzi->get());
    }

    protected function setPagamenti()
    {
        $pagamenti = new Template("checkout/pagamenti.html");

        $query = "SELECT `metodipagamento`.* FROM `utenti` ".
            " JOIN `metodipagamento` ON `utenti`.id = `metodipagamento`.utente_id ".
            " WHERE `utenti`.id = :uid";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['uid' => $this->auth->getCurrentUID()]);

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $pagamenti->setContent($row, null);
        }

        $this->body->setContent("pagamenti", $pagamenti->get());
    }
}