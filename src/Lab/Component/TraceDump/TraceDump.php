<?php

namespace Lab\Component\TraceDump;

use Lab\Component\TraceDump\Styler\CliStyler;
use Lab\Component\TraceDump\Styler\Coloring\ColoringFactory;

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
     * {@inheritdoc}
     */
    public static function tracedump()
    {
        if (self::isCli()) {
            ColoringFactory::setColoringClass(self::isWindows() ? ColoringFactory::NONE : ColoringFactory::CLI);

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

    /**
     * @return bool
     */
    public static function isWindows()
    {
        return preg_match('/WIN/', PHP_OS);
    }

}
