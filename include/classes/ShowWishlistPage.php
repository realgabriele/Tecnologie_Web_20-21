<?php

require "ShowPage.php";

class ShowWishlistPage extends ShowPage
{
    protected $can_edit = true;

    public function check_authorization($actions = [])
    {
        if (!isset($this->table_name)) $this->render_error("Tabella non specificata");

        $actions = array_merge($actions, [$this->table_name . '.show']);
        foreach ($actions as $action){
            $id = $this->auth->isAuthenticated ? $this->auth->getCurrentUID() : 0;
            if (!$this->auth->is_authorized($id, $action)) {
                $this->render_error("Operazione non autorizzata su questa tabella");
            }
        }

        if ($this->single_page){
            // controlla se l'utente loggato è il possessore di quella entry
            $query = "SELECT `utenti`.id FROM `utenti` ".
                " JOIN `{$this->table_name}` ON `utenti`.id = `{$this->table_name}`.utente_id ".
                " WHERE `utenti`.id = :uid AND `{$this->table_name}`.id = :id";
            $query_prepared = $this->dbh->prepare($query);
            $query_prepared->execute(['uid' => $this->auth->getCurrentUID(), 'id' => $this->row_id]);
            if ($query_prepared->rowCount() > 0) return;

            // controlla se all'utente loggato è stata condivisa quella entry
            $query = "SELECT `utenti`.id FROM `utenti` ".
                " JOIN `{$this->table_name}_condivisione` ON `utenti`.id = `{$this->table_name}_condivisione`.utente_id ".
                " WHERE `utenti`.id = :uid AND `{$this->table_name}_condivisione`.{$this->table_name}_id = :rid";
            $query_prepared = $this->dbh->prepare($query);
            $query_prepared->execute(['uid' => $this->auth->getCurrentUID(), 'rid' => $this->row_id]);
            if ($query_prepared->rowCount() > 0) {
                $this->can_edit = false;
                return;
            }

            $this->render_error("Non autorizzato a visualizzare questa entry");
        }
    }

    public function handleRequest()
    {
        if ($this->can_edit) {
            if (isset($_POST['add'])) {
                // insert articolo_wishlist entry
                $query = "INSERT INTO `articolo_wishlist` (wishlist_id, articolo_id) VALUES (?, ?)";
                $query_prepared = $this->dbh->prepare($query);
                $query_prepared->execute([$this->row_id, $_POST['add']]);
            }

            if (isset($_POST['del'])) {
                // remove articolo_wishlist entry
                $query = "DELETE FROM `articolo_wishlist` WHERE wishlist_id = ? AND articolo_id = ?";
                $query_prepared = $this->dbh->prepare($query);
                $query_prepared->execute([$this->row_id, $_POST['del']]);
            }
        }
    }

    protected function single_page_body()
    {
        parent::single_page_body();

        if ($this->can_edit) $this->body->setContent("can_edit", true);

        // show : articolo_ordine + articoli
        $query = "SELECT aw.*, id, foto, nome, prezzo ".
            " FROM articolo_wishlist AS aw ".
            " LEFT JOIN articoli ".
            " ON aw.articolo_id = articoli.id ".
            " WHERE wishlist_id = :wid";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['wid' => $this->row_id]);

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $this->body->setContent(array_key_append($row, "_articolo"), null);
        }
    }

    protected function multiple_page_body()
    {
        parent::multiple_page_body();

        $query = "SELECT `{$this->table_name}`.* FROM `utenti` ".
            " JOIN `{$this->table_name}_condivisione` ON `utenti`.id = `{$this->table_name}_condivisione`.utente_id ".
            " JOIN `{$this->table_name}` ON `{$this->table_name}_condivisione`.{$this->table_name}_id = `{$this->table_name}`.id ".
            " WHERE `utenti`.id = :uid";
        $query_prepared = $this->dbh->prepare($query);
        $query_prepared->execute(['uid' => $this->auth->getCurrentUID()]);

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $this->body->setContent(array_key_append($row, "_shared"), null);
        }
    }
}