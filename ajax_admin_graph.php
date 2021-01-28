<?php

session_start();

// handle AJAX request for adding/removing rows in associations tables
require "include/dbms.inc.php";
require "include/classes/PHPAuth/Auth.php";
require "include/classes/PHPAuth/Config.php";

global $dbh;
$auth = new PHPAuth\Auth();

/* check authorization */
$actions = ['backoffice'];
foreach ($actions as $action) {
    $id = $auth->isAuthenticated ? $auth->getCurrentUID() : 0;
    if (!$auth->is_authorized($id, $action)) {
        echo("Operazione non autorizzata!");
    }
}


$query = "SELECT DATE(timestamp) dt, COUNT(1) conta " .
    " FROM ordini " .
    " WHERE timestamp >= DATE(NOW()) - INTERVAL 1 WEEK + INTERVAL 0 SECOND " .
    " GROUP BY DATE(timestamp) ";

$query_prepared = $dbh->prepare($query);
if (!$query_prepared->execute()) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode($query_prepared->errorInfo()));
}

$data = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
$array = array(
    "status" => "success",
    "data" => json_encode($data),
    "labels" => json_encode(array_column($data, "dt")),
    "values" => json_encode(array_column($data, "conta")),
);
echo json_encode($array);
exit();

