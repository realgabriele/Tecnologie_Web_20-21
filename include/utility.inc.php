<?php

function array_key_append($array, $string){
    $return = array();
    foreach($array as $key => $value){
        $return[$key.$string] = $value;
    }
    return $return;
}
