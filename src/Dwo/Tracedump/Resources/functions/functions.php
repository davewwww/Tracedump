<?php

use Dwo\Tracedump\Tracedump;

function td()
{
    if(!headers_sent()) {
        header('HTTP/1.1 500');
    }

    die(tdr(func_get_args()));
}

function tde()
{
    echo(tdr(func_get_args()));
}

/**
 * @param mixed $funcArgs
 *
 * @return mixed
 */
function tdr($funcArgs = null)
{
    $class = new Dwo\Tracedump\Tracedump();
    $funcArgs = null !== $funcArgs ? $funcArgs : func_get_args();

    return call_user_func_array(array($class, 'tracedump'), $funcArgs);
}