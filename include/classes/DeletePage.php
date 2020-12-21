<?php

require "FramePublic.php";

class DeletePage extends FramePublic
{
    protected $table_name = null;
    protected $row_id = null;

    public function __construct()
    {
        $this->table_name = $_GET['table'] ?? $_POST['table'];
        $this->row_id = $_GET['id'] ?? $_POST['id'];

        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");
        if (!isset($this->row_id)) $this->render_error("ID non specificato");

        parent::check_authorization([$this->table_name . ".delete"]);

        $query = "SELECT `utenti`.id FROM `utenti` ".
            " JOIN `{$this->table_name}` ON `utenti`.id = `{$this->table_name}`.utente_id ".
            " WHERE `utenti`.id = :uid AND `{$this->table_name}`.id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['uid' => $this->auth->getCurrentUID(), 'id' => $this->row_id]);

        if ($query_prepared->rowCount() == 0) {
            $this->render_error("utente non autorizzato ad eliminare questo elemento");
        }
    }

    public function handleRequest()
    {
            $query = "DELETE FROM {$this->table_name} WHERE id = ?";

            $query_prepared = $this->dbh->prepare($query);

            if (!$query_prepared->execute([$this->row_id])) {
                print_r($query_prepared->errorInfo());
                $this->render_error("database error: "  . $query_prepared->errorCode());
            } else {
                $this->body->setContent("success_msg", "Eliminato con successo");
            }
    }

    public function updateBody()
    {
        //ToDo: redirect
    }
}