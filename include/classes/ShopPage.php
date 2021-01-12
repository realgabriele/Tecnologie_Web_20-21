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
        $conditions = [];
        $parameters = [];
        $joins = [];
        $query = "SELECT * FROM `articoli` ";

        if (isset($_GET['q'])) {        // query string
            $conditions[] = " ( nome LIKE :q OR ".
                " descrizione LIKE :q OR ".
                " descrizione_lunga LIKE :q ) ";
            $parameters['q'] = "%" . $_GET['q'] . "%";
            $parameters['q'] = "%" . $_GET['q'] . "%";
            $parameters['q'] = "%" . $_GET['q'] . "%";


        }
        if (isset($_GET['cat'])) {      // category id
            $joins[] = " JOIN `articolo_categoria` AS AC ON id = AC.articolo_id ";
            $conditions[] = " AC.categoria_id = :cat ";
            $parameters['cat'] = $_GET['cat'];
        }

        if (!empty($joins)) {
            $query .= implode("", $joins);
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        echo "\nquery\n";
        print_r($query);
        echo "\njoins\n";
        print_r($joins);
        echo "\nconditions\n";
        print_r($conditions);
        echo "\nparameters\n";
        print_r($parameters);
        echo "\n\n";

        $this->query_prepared = $this->dbh->prepare($query);
        foreach ($parameters as $param => &$value) {
            $this->query_prepared->bindParam($param, $value);
        }

    }

    public function updateBody()
    {
        $this->body->setContent(array_key_append($_GET, "_old"), null);

        if (! $this->query_prepared->execute()) {
            echo "Error: ";
            print_r($this->query_prepared->errorInfo());
        }

        $data = $this->query_prepared->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $row) {
            $this->body->setContent($row, null);
        }
    }
}
