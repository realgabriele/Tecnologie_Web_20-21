<?php

Class utility extends TagLibrary {

    function dummy() {}

    function upper($name, $data, $pars) {
        return $data;
    }

    function to_prezzo($name, $data, $pars) {
        return "€ " . number_format($data, 2);
    }

    /** given a sql timestamp, returns the date in gg/mm/yyyy format */
    function to_date($name, $data, $pars) {
        return date("d/m/Y", strtotime($data));
    }

    /** given a sql timestamp, returns the time in mm:ss format */
    function to_time($name, $data, $pars) {
        return date("G:i", strtotime($data));
    }

    function hide_cardnumber($name, $data, $pars) {
        return str_repeat("*", strlen($data)-4) . substr($data, strlen($data)-4);
    }

}

?>