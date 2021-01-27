<?php

class DeleteAOBackPage extends DeleteBackPage
{
    public function handleRequest()
    {
        $query = "SELECT ordine_id FROM articolo_ordine WHERE id=?";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute([$this->row_id]);
        $order_id  = $query_prepared->fetch()[0];

        $query = "DELETE FROM {$this->table_name} WHERE id = ?";

        $query_prepared = $this->dbh->prepare($query);

        if (!$query_prepared->execute([$this->row_id])) {
            print_r($query_prepared->errorInfo());
            $this->render_error("database error: "  . $query_prepared->errorCode());
        } else {
            $this->body->setContent("success_msg", "Eliminato con successo");
            // redirect alla pagina show_multiple
            header("location: admin_show.php?table=ordini&id={$order_id}");
            die();
        }
    }
}