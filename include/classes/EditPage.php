<?php

require "FramePublic.php";

class EditPage extends FramePublic
{
    protected $table_name = null;
    protected $row_id = null;

    public function __construct()
    {
        $this->table_name = $_GET['table'] ?? $_POST['table'];
        $this->row_id = $_GET['id'] ?? $_POST['id'];

        $this->body = new Template($this->table_name . "/edit.html");

        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");
        if (!isset($this->row_id)) $this->render_error("ID non specificato");

        parent::check_authorization([$this->table_name . ".edit"]);

        $query = "SELECT `utenti`.id FROM `utenti` ".
            " JOIN `{$this->table_name}` ON `utenti`.id = `{$this->table_name}`.utente_id ".
            " WHERE `utenti`.id = :uid AND `{$this->table_name}`.id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['uid' => $this->auth->getCurrentUID(), 'id' => $this->row_id]);

        if ($query_prepared->rowCount() == 0) {
            $this->render_error("utente non autorizzato a modificare questa pagina");
        }
    }

    public function handleRequest()
    {
        $params = $_POST;

        if (isset($params['id'])) { // se le varibili da modificare sono settate
            unset($params['table']);
            unset($params['id']);

            $setParams = implode(', ', array_map(function ($key, $value) {
                return $key . ' = ?';
            }, array_keys($params), $params));

            $query = "UPDATE {$this->table_name} SET {$setParams} WHERE id = ?";

            $query_prepared = $this->dbh->prepare($query);
            $bindParams = array_values(array_merge($params, [$this->row_id]));

            if (!$query_prepared->execute($bindParams)) {
                $this->render_error("database error: "  . $query_prepared->errorCode());
            } else {
                $this->body->setContent("success_msg", "Salvato con successo");
            }
        }
    }

    public function updateBody()
    {
        $query = "SELECT * FROM `{$this->table_name}` ".
            " WHERE `{$this->table_name}`.id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['id' => $this->row_id]);

        $row = $query_prepared->fetch(PDO::FETCH_ASSOC);
        $this->body->setContent($row, null);
    }
}