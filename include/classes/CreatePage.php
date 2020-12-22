<?php

require "FramePublic.php";

class CreatePage extends FramePublic
{
    protected $table_name = null;
    protected $new_row_id = null;

    public function __construct()
    {
        $this->table_name = $_GET['table'] ?? $_POST['table'];

        $this->body = new Template($this->table_name . "/create.html");

        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");

        parent::check_authorization([$this->table_name . ".create"]);
    }

    public function handleRequest()
    {
        $params = $_POST;

        if (isset($params['id'])) { // se le varibili da modificare sono settate
            unset($params['table']);
            unset($params['id']);
            $params['utente_id'] = $this->auth->getCurrentUID();

            $insertCol = implode(', ', array_keys($params));
            $insertVal = implode(', ', array_map(function ($value) {
                return '?';
            }, $params));

            $query = " INSERT INTO {$this->table_name} ({$insertCol}) VALUES ({$insertVal})";

            $query_prepared = $this->dbh->prepare($query);
            $bindParams = array_values($params);

            if (!$query_prepared->execute($bindParams)) {
                // $this->render_error("database error: "  . $query_prepared->errorCode());
                print_r($query_prepared->errorInfo());
                $this->body->setContent("error_msg", "database error: "  . $query_prepared->errorCode());
            } else {
                $this->body->setContent("success_msg", "Creato con successo");
                // redirect alla pagina show_single
                $this->new_row_id = $this->dbh->lastInsertId();
                header("location: show.php?table={$this->table_name}&id={$this->new_row_id}");
                die();
            }
        }
    }

    public function updateBody()
    {
        echo $this->new_row_id;

        $this->body->setContent(array_key_append($_POST, "-old"), null);
    }
}