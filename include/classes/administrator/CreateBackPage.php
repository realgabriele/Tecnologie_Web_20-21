<?php

require "FramePrivate.php";

class CreateBackPage extends FramePrivate
{
    protected $table_name = null;
    protected $new_row_id = null;

    public function __construct($table_name)
    {
        $this->table_name = $table_name;
        $this->body = new Template($this->table_name . "/create.html");

        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");

        parent::check_authorization(array_merge($actions, ['backoffice' . $this->table_name . '.create']));
    }

    public function handleRequest()
    {
        $params = $_POST;

        if (isset($_FILES['image'])) {
            $params['image'] = $this->save_image();
        }

        if (sizeof($params) > 0) { // se le varibili da modificare sono settate
            unset($params['table']);
            unset($params['id']);

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
        $this->body->setContent(array_key_append($_POST, "_old"), null);
    }

    protected function save_image()
    {
        $imagePath = "skins/assets/";
        $imageName = pathinfo($_FILES['image']['tmp_name'], PATHINFO_FILENAME);
        $imageExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        // prevent overwrite of existing file
        $i = '';
        while(file_exists($imageName . $i . '.' . $imageExt)) {
            $i++;
        }

        $basename = $imageName . $i . '.' . $imageExt;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath . $basename))
            return $imagePath . $basename;
        else return "";
    }
}