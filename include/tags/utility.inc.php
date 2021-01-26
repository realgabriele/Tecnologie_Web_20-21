<?php

Class utility extends TagLibrary {

    function dummy() {}

    function upper($name, $data, $pars) {
        return $data;
    }

    function shorten($name, $data, $pars) {
        if (strlen($data) <= $pars['length']) return $data;
        return substr($data, 0, $pars['length']) . " ...";
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

    /** rating value to stars elements */
    function to_stars($name, $data, $pars) {
        $content = "";
        if ($data > 0) {
            for ($i = 0; $i < 5; $i++) {
                // append a star, solid or regular
                $content .= $i < $data ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
            }
        }
        return $content;
    }
}

?>