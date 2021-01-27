<?php

require "include/config.inc.php";
require "include/template2.inc.php";
require "include/dbms.inc.php";

session_start();


$main = new Template("frame-public.html");
$body = new Template("areautente.html");

$id = $_SESSION['auth_uid'];

$query = "SELECT utenti.nome as nome, utenti.email as email, indirizzi.via, indirizzi.citta, indirizzi.provincia, indirizzi.cap, intestatario_carta, numero_carta, scadenza_carta 
FROM utenti, indirizzi, metodipagamento WHERE indirizzi.utente_id = utenti.id AND utenti.id = metodipagamento.utente_id  GROUP BY indirizzi.via";

global $dbh;

    //"SELECT utenti.nome as nome, utenti.email as email, indirizzi.via, indirizzi.citta, indirizzi.provincia, indirizzi.cap, intestatario_carta, numero_carta, scadenza_carta
 //FROM utenti INNER JOIN indirizzi ON indirizzi.utente_id = utenti.id INNER JOIN metodipagamento ON utenti.id = metodipagamento.utente_id WHERE utenti.id=$id";




    if ($res = $dbh->query($query)) {
        for($i=0; $i<$res->rowCount(); $i++) {
            $content = $res->fetch(PDO::FETCH_ASSOC);

            if (isset($content['nome']))
                $nome = $content['nome'];

            if (isset($content['email']))
                $email = $content['email'];

            if (isset($content['via']))
                $body->setContent('via', $content['via']);

            if (isset($content['citta']))
                $body->setContent('citta', $content['citta']);

            if (isset($content['cap']))
                $body->setContent('cap', $content['cap']);

            if (isset($content['intestatario_carta']))
                $body->setContent('intestatario_carta', $content['intestatario_carta']);

            if (isset($content['numero_carta']))
                $body->setContent('numero_carta', $content['numero_carta']);

            if (isset($content['scadenza_carta']))
                $body->setContent('scadenza_carta', $content['scadenza_carta']);

            if (isset($nome))
                $body->setContent("nome", $nome);

            if (isset($email))
                $body->setContent("email", $email);
            else {
                print_r($dbh->errorInfo());

            }
        }
    }



$main->setContent("body", $body->get());
$main->close();
