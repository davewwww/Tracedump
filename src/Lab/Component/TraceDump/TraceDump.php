<?php

namespace Lab\Component\TraceDump;

use Lab\Component\TraceDump\Dumper\Dumper;

/**
 * @author David Wolter <david@dampfer.net>
 */
class TraceDump
{
    /**
     * @return string
     */
    public static function tracedump()
    {
        $styler = Styler::getStyler();

        $dumps = array();
        foreach (func_get_args() as $arg) {
            $dumps[] = Drawer::draw(Dumper::dump($arg), $styler);
        }

        return $styler->dump($dumps);
    }

    /**
     * @param bool $force
     */
    public static function forceCli($force)
    {
        Styler::forceCli($force);
    }
}
