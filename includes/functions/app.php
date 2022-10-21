<?php

function debug(...$var) {
    foreach ($var as $value) {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }
}

?>