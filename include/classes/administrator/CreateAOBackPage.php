<?php

class CreateAOBackPage extends CreateBackPage
{
    protected $order_id = null;

    public function __construct($table_name, $order_id)
    {
        $this->order_id = $order_id;
        parent::__construct($table_name);
    }

    public function handleRequest()
    {
        if (isset($_POST['ordine_id']) && isset($_POST['articolo_id'])) { // se le varibili da modificare sono settate

            $query = "INSERT INTO `articolo_ordine` ".
                " (ordine_id, articolo_id, quantita, prezzo) ".
                " SELECT :ordine_id, `articoli`.id, 1, `articoli`.prezzo ".
                " FROM `articoli` ".
                " WHERE `articoli`.id = :articolo_id";

            $query_prepared = $this->dbh->prepare($query);
            $bindParams = ["ordine_id"=>$_POST['ordine_id'], "articolo_id"=>$_POST['articolo_id']];

            if (!$query_prepared->execute($bindParams)) {
                // $this->render_error("database error: "  . $query_prepared->errorCode());
                print_r($query_prepared->errorInfo());
                $this->body->setContent("error_msg", "database error: "  . $query_prepared->errorCode());
            } else {
                $this->body->setContent("success_msg", "Creato con successo");
                // redirect alla pagina show_single
                $this->new_row_id = $this->dbh->lastInsertId();
                header("location: admin_edit.php?table={$this->table_name}&id={$this->new_row_id}");
                die();
            }
        }
    }

    public function updateBody()
    {
        parent::updateBody();
        $this->body->setContent("ordine_id", $this->order_id);

        // set all the articles in DB
        $query = "SELECT * FROM `articoli`";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute();

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $this->body->setContent($row, null);
        }

    }
}