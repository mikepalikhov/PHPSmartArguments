<?php

namespace MikePal;

class ArrArgs {
    function __construct() {
    }

    static public HandleArgs($args) {
        echo("Handled !!!<br>");
        print_r($args);
    }
}