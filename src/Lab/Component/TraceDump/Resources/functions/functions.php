<?php

function td()
{
    $class = new Lab\Component\TraceDump\TraceDump();
    $dump = call_user_func_array(array($class, 'tracedump'), func_get_args());

    try {
        header("HTTP/1.1 500");
    } catch (\Exception $e) {
    }
    die($dump);
}

function tde()
{
    $class = new Lab\Component\TraceDump\TraceDump();
    $dump = call_user_func_array(array($class, 'tracedump'), func_get_args());
    echo($dump);
}
