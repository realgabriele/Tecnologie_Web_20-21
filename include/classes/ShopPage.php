<?php

require "FramePublic.php";

class ShopPage extends FramePublic
{
    protected $query_prepared;

    public function __construct()
    {
        $this->body = new Template("shop.html");
        $this->query_prepared = PDOStatement::class;
        parent::__construct();
    }

    public function handleRequest()
    {
        $filtered_get = array_filter($_GET);

        $query = "SELECT `articoli`.*, AVG(rating) AS rating FROM `articoli` ";
        $conditions = [];
        $parameters = [];
        $joins = [];

        // recensioni
        $joins[] = " LEFT JOIN `recensioni` ON `articoli`.id = `recensioni`.articolo_id ";

        if (isset($filtered_get['q'])) {        // query string
            $conditions[] = " ( nome LIKE :q OR ".
                " descrizione LIKE :q OR ".
                " descrizione_lunga LIKE :q ) ";
            $parameters['q'] = "%" . $filtered_get['q'] . "%";
        }

        if (isset($filtered_get['cat'])) {      // category id
            $joins[] = " JOIN `articolo_categoria` AS AC ON `articoli`.id = AC.articolo_id ";

            $conds = [];
            foreach ($filtered_get['cat'] as $i => $cat){
                $conds[] = " AC.categoria_id = :cat{$i} ";
                $parameters["cat{$i}"] = $cat;
            }
            $conditions[] = " ( " . implode(" OR ", $conds) . " ) ";
        }

        /* prepare query */
        if (!empty($joins)) {
            $query .= implode("", $joins);
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " GROUP BY `articoli`.id ";  // no duplicates

        $this->query_prepared = $this->dbh->prepare($query);
        foreach ($parameters as $param => &$value) {
            $this->query_prepared->bindParam($param, $value);
        }

    }

    public function updateBody()
    {
        $this->body->setContent(array_key_append($_GET, "_old"), null);

        // set Articoli mostrati
        if (! $this->query_prepared->execute()) {
            echo "Error: ";
            print_r($this->query_prepared->errorInfo());
        }
        $data = $this->query_prepared->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $row) {
            $this->body->setContent($row, null);
        }

        // set lista di Categorie
        if (!$result = $this->dbh->query("SELECT * FROM `categorie`")){
            echo "Error: "; print_r($this->dbh->errorInfo());
        }
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $row) {
            $row['check'] = in_array($row['id'], $_GET['cat'] ?? []) ? "checked" : "";
            $this->body->setContent(array_key_append($row, "_cat"), null);
        }
    }
}
