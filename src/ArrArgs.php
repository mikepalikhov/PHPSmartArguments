<?php

namespace MikePal;

class ArrArgs {
    function __construct() {
    }

    static public function HandleArgs($args) {
        echo("Handled !!!<br>");
        print_r($args);
    }
}