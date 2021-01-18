<?php

require "FramePublic.php";

class ShowPage extends FramePublic
{
    protected $table_name;
    protected $row_id;
    protected $single_page = false;

    public function __construct()
    {
        $this->table_name = $_GET['table'] ?? null;
        $this->row_id = $_GET['id'] ?? null;
        $this->single_page = isset($_GET['id']);

        $this->body = new Template($this->table_name . "/show_" . ($this->single_page ? "single" : "multiple") . ".html");

        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");

        parent::check_authorization(array_merge($actions, [$this->table_name . '.show']));

        if ($this->single_page){
            // controlla se l'utente loggato è il possessore di quella entry
            $query = "SELECT `utenti`.id FROM `utenti` ".
                " JOIN `{$this->table_name}` ON `utenti`.id = `{$this->table_name}`.utente_id ".
                " WHERE `utenti`.id = :uid AND `{$this->table_name}`.id = :id";
            $query_prepared = $this->dbh->prepare($query);
            $query_prepared->execute(['uid' => $this->auth->getCurrentUID(), 'id' => $this->row_id]);
            if (!$query_prepared->rowCount() > 0) {
                $this->render_error("Non autorizzato a visualizzare questa entry");
            }
        }
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
        $query = "SELECT `{$this->table_name}`.* FROM `utenti` ".
            " JOIN `{$this->table_name}` ON `utenti`.id = `{$this->table_name}`.utente_id ".
            " WHERE `utenti`.id = :uid";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['uid' => $this->auth->getCurrentUID()]);

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $this->body->setContent($row, null);
        }
    }

}