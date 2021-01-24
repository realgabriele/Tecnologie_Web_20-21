<?php

require "FramePrivate.php";

class ShowBackPage extends FramePrivate
{
    protected $table_name;
    protected $row_id;
    protected $single_page = false;

    public function __construct($table_name, $row_id=null)
    {
        $this->table_name = $table_name;
        $this->row_id = $row_id;
        $this->single_page = isset($_GET['id']);

        $this->body = new Template($this->table_name . "/show_" . ($this->single_page ? "single" : "multiple") . ".html");

        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");

        parent::check_authorization(array_merge($actions, ['backoffice' . $this->table_name . '.show']));
    }

    public function updateBody()
    {
        if ($this->single_page) {
            $this->single_page_body();
        } else {
            $this->multiple_page_body();
        }
    }

    protected function single_page_body()
    {
        $query = "SELECT * FROM `{$this->table_name}` ".
            " WHERE `{$this->table_name}`.id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['id' => $this->row_id]);

        $row = $query_prepared->fetch(PDO::FETCH_ASSOC);
        $this->body->setContent($row, null);
    }

    protected function multiple_page_body()
    {
        $query = "SELECT * FROM {$this->table_name}";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute();

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $this->body->setContent($row, null);
        }
    }

}