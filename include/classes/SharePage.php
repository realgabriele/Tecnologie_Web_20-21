<?php

require "FramePublic.php";

class SharePage extends FramePublic
{
    protected $table_name;
    protected $row_id;

    public function __construct()
    {
        $this->table_name = $_GET['table'];
        $this->row_id = $_GET['id'];

        $this->body = new Template($this->table_name . "/share.html");
        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");

        parent::check_authorization([$this->table_name . '.share']);

        // controlla se l'utente loggato è il possessore di quella entry
        $query = "SELECT `utenti`.id FROM `utenti` ".
            " JOIN `{$this->table_name}` ON `utenti`.id = `{$this->table_name}`.utente_id ".
            " WHERE `utenti`.id = :uid AND `{$this->table_name}`.id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['uid' => $this->auth->getCurrentUID(), 'id' => $this->row_id]);

        if (!$query_prepared->rowCount() > 0) {
            $this->render_error("Non autorizzato a condividere questa entry");
        }
    }

    public function handleRequest()
    {
        $params = $_POST;

        if (isset($params['add'])){
            $query = "INSERT INTO `{$this->table_name}_condivisione` ".
                " ({$this->table_name}_id, utente_id) ".
                " SELECT :row_id, `utenti`.id ".
                " FROM `utenti` ".
                " WHERE email = :email";
            $query_prepared = $this->dbh->prepare($query);
            $query_prepared->execute(['row_id' => $this->row_id, 'email' => $params['add']]);
        }

        if (isset($_POST['del'])) {
            $query = "DELETE FROM `{$this->table_name}_condivisione` ".
                " WHERE {$this->table_name}_id = :row_id AND utente_id = :uid";

            $query_prepared = $this->dbh->prepare($query);
            $query_prepared->execute(['row_id' => $this->row_id, 'uid' => $params['del']]);
        }
    }

    public function updateBody()
    {
        // wishlist data
        $query = "SELECT * FROM `{$this->table_name}` ".
            " WHERE `{$this->table_name}`.id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['id' => $this->row_id]);
        $row = $query_prepared->fetch(PDO::FETCH_ASSOC);
        $this->body->setContent(array_key_append($row, "_wishlist"), null);

        // utenti condivisi data
        $query = "SELECT * ".
            " FROM `{$this->table_name}_condivisione` ".
            " JOIN `utenti` ON utente_id = `utenti`.id ".
            " WHERE {$this->table_name}_id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['id' => $this->row_id]);
        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $this->body->setContent(array_key_append($row, "_utente"), null);
        }
    }
}