<?php

require "FramePublic.php";

class WriteReviewPage extends FramePublic
{
    protected $review_id;
    protected $item_id;

    public function __construct()
    {
        $this->body = new Template("recensioni/write.html");
        $this->item_id = $_GET['id'] ?? $_POST['id'] ?? null;

        parent::__construct();
    }

    public function check_authorization($actions = [])
    {
        if (!isset($this->item_id)) $this->render_error("ID non specificato");
        parent::check_authorization(array_merge($actions, ["recensioni.write"]));
    }

    public function handleRequest()
    {
        $params = $_POST;

        // get rewiew id
        $query = "SELECT id FROM `recensioni` WHERE articolo_id = :aid AND utente_id = :uid";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['aid'=>$this->item_id, 'uid'=>$this->auth->getCurrentUID()]);

        $this->review_id = $query_prepared->rowCount() == 0 ? null : $query_prepared->fetch()[0];

        if (isset($params['id'])) { // se le varibili da modificare sono settate
            unset($params['id']);

            if ($this->review_id) $this->update_review($params);
            else $this->create_review($params);
        }
    }

    private function create_review($params) {
        $insertCol = implode(', ', array_keys($params));
        $insertVal = implode(', ', array_map(function ($value) {
            return '?';
        }, $params));

        $query = " INSERT INTO `recensioni` ({$insertCol}) VALUES ({$insertVal})";
        $query_prepared = $this->dbh->prepare($query);
        $bindParams = array_values($params);

        if (!$query_prepared->execute($bindParams)) {
            $this->render_error("database error: "  . $query_prepared->errorCode());
            $this->body->setContent("error_msg", "database error: "  . $query_prepared->errorCode());
        } else {
            $this->body->setContent("success_msg", "Creato con successo");
        }
    }

    private function update_review($params) {
        $setParams = implode(', ', array_map(function ($key, $value) {
            return $key . ' = ?';
        }, array_keys($params), $params));

        $query = "UPDATE `recensioni` SET {$setParams} WHERE id = ?";
        $query_prepared = $this->dbh->prepare($query);
        $bindParams = array_values(array_merge($params, [$this->review_id]));

        if (!$query_prepared->execute($bindParams)) {
            $this->render_error("database error: " . $query_prepared->errorCode());
        } else {
            $this->body->setContent("success_msg", "Salvato con successo");
        }
    }

    public function updateBody()
    {
        // fetch Review
        $query = "SELECT * FROM `recensioni` WHERE id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['id' => $this->review_id]);

        $row = $query_prepared->fetch(PDO::FETCH_ASSOC);
        $this->body->setContent($row, null);

        // fetch Item
        $query = "SELECT * FROM `articoli` WHERE id = :id";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['id' => $this->item_id]);

        $row = $query_prepared->fetch(PDO::FETCH_ASSOC);
        $this->body->setContent($row, null);
    }

}