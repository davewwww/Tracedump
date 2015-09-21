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

    /**
     * @return array
     */
    public static function calledIn()
    {
        $debugs = (array) debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);

        $key = null;
        foreach ($debugs as $key => $debug) {
            $str = isset($debug['file']) ? $debug['file'] : '';
            $str.= isset($debug['class']) ? $debug['class'] : '';
            if (!preg_match('/tracedump/i', $str)) {
                break;
            }
        }

        $file = $debugs[$key]['file'];
        $line = $debugs[$key]['line'];
        if (null !== $key) {
            if (isset($debugs[$key + 1]['class'])) {
                $file = $debugs[$key + 1]['class'];
            }
        }

        return sprintf('called in "%s" at line %s', $file, $line);
    }
}
