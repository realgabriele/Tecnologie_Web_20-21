<?php

require "FramePrivate.php";

class DeleteBackPage extends FramePrivate
{
    protected $table_name = null;
    protected $row_id = null;

    public function __construct($table_name, $row_id)
    {
        $this->table_name = $table_name;
        $this->row_id = $row_id;

        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");
        if (!isset($this->row_id)) $this->render_error("ID non specificato");

        parent::check_authorization(array_merge($actions, ['backoffice.' . $this->table_name . '.delete']));
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
                // redirect alla pagina show_multiple
                if (isset($_GET['user_id']))
                    header("location: admin_show.php?table=utenti&id={$_GET['user_id']}");
                else header("location: admin_show.php?table={$this->table_name}");
                die();
            }
    }
}