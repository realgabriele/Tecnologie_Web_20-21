<?php
session_start();

// handle AJAX request for adding/removing rows in associations tables
require "include/dbms.inc.php";
require "include/classes/PHPAuth/Auth.php";
require "include/classes/PHPAuth/Config.php";

global $dbh;
$auth = new PHPAuth\Auth($dbh, new PHPAuth\Config($dbh));

/* check authorization */
/*$actions = ['backoffice', "backoffice.{$_POST['assoc_name']}.edit"];
print_r($actions);
foreach ($actions as $action){
    $id = $auth->isAuthenticated ? $auth->getCurrentUID() : 0;
    if (!$auth->is_authorized($id, $action)) {
        echo("Operazione non autorizzata!");
    }
}*/

$type = $_POST['type'];
$action = $_POST['action'];

if ($action=="create" && $type=="many2many"){
    $query = " INSERT INTO {$_POST['assoc_name']} ".
        " ({$_POST['tab1id_name']}_id, {$_POST['tab2id_name']}_id) ".
        " VALUES ({$_POST['tab1_id']}, {$_POST['tab2_id']})";
    $query_prepared = $dbh->prepare($query);
    if (!$query_prepared->execute()) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($query_prepared->errorInfo()));
    }
}
if ($action=="delete" && $type=="many2many"){
    $query = " DELETE FROM {$_POST['assoc_name']} ".
        " WHERE {$_POST['tab1id_name']}_id = {$_POST['tab1_id']} ".
        " AND {$_POST['tab2id_name']}_id = {$_POST['tab2_id']} ";
    $query_prepared = $dbh->prepare($query);
    if (!$query_prepared->execute()) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($query_prepared->errorInfo()));
    }
}


header('Content-Type: application/json');
$array = array(
    "status" => "success",
    "auth_id" => $auth->getCurrentUID(),
);
echo json_encode($array);
exit();

