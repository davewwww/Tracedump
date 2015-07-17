<?php

namespace Dwo\Tracedump;

use Dwo\Tracedump\Dumper\Dumper;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class Tracedump
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
