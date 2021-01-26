<?php

require "FramePrivate.php";

class EditBackPage extends FramePrivate
{
    protected $table_name = null;
    protected $row_id = null;

    public function __construct($table_name, $row_id)
    {
        $this->table_name = $table_name;
        $this->row_id = $row_id;

        $this->body = new Template($this->table_name . "/edit.html");

        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");
        if (!isset($this->row_id)) $this->render_error("ID non specificato");

        parent::check_authorization(array_merge($actions, ['backoffice.' . $this->table_name . '.edit']));

    }

    public function handleRequest()
    {
        $params = $_POST;

        if (isset($_FILES['foto']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
            $params['foto'] = $this->save_image();
        } else { unset($params['foto']); }

        if (sizeof($params) > 0) { // se le varibili da modificare sono settate
            unset($params['table']);
            unset($params['id']);

            $setParams = implode(', ', array_map(function ($key, $value) {
                return $key . ' = ?';
            }, array_keys($params), $params));

            $query = "UPDATE {$this->table_name} SET {$setParams} WHERE id = ?";

            $query_prepared = $this->dbh->prepare($query);
            $bindParams = array_values(array_merge($params, [$this->row_id]));

            if (!$query_prepared->execute($bindParams)) {
                $this->render_error("database error: " . $query_prepared->errorCode());
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

    protected function save_image()
    {
        $imagePath = "skins/assets/";
        $imageName = pathinfo($_FILES['foto']['tmp_name'], PATHINFO_FILENAME);
        $imageExt = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        // prevent overwrite of existing file
        $i = '';
        while(file_exists($imageName . $i . '.' . $imageExt)) {
            $i++;
        }

        $basename = $imageName . $i . '.' . $imageExt;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $imagePath . $basename))
            return "../assets/" . $basename;
        else return null;
    }
}