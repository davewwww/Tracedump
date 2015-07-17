<?php

function td()
{
    try {
        header('HTTP/1.1 500');
    } catch (\Exception $e) {
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