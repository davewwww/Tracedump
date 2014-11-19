<?php

namespace Lab\Component\TraceDump;

use Lab\Component\TraceDump\Styler\CliStyler;

/**
 * @author David Wolter <david@dampfer.net>
 */
class TraceDump
{
    /**
     * @var bool
     */
    private static $forceCli = false;

    /**
     * @return mixed
     */
    public static function tracedump()
    {
        if (self::isCli()) {
            $class = new Cli(new CliStyler());
        } else {
            $class = new Html();
        }

        return call_user_func_array(array($class, 'tracedump'), func_get_args());
    }

    /**
     * @param bool $force
     */
    public static function forceCli($force)
    {
        self::$forceCli = $force;
    }

    /**
     * @return bool
     */
    public static function isCli()
    {
        return 'cli' === PHP_SAPI || self::$forceCli;
    }

}
