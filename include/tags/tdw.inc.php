<?php

Class tdw extends TagLibrary {

    function dummy() {}

    function upper($name, $data, $pars) {

        return $data;
    }

    function to_prezzo($name, $data, $pars) {
        return number_format($data, 2);
    }

}

?>