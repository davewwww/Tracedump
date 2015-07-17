<?php

namespace Dwo\Tracedump;

use Dwo\Tracedump\Drawer\ArrayDrawer;
use Dwo\Tracedump\Drawer\ObjectDrawer;
use Dwo\Tracedump\Styler\StylerInterface;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class Drawer
{
    /**
     * @param array $dump
     *
     * @return string
     */
    public static function draw(array $dump, StylerInterface $styler)
    {
        $type = $dump["type"];
        $value = $dump["value"];

        switch ($type) {
            case "array":
                $drawer = new ArrayDrawer($styler);
                $lines = $drawer->draw($value);
                break;

            case "object":
                $drawer = new ObjectDrawer($styler);
                $lines = $drawer->draw($value, 0, 4);
                break;

            default:
                $value = $styler->style($type, $value);
                $lines = array($value);
                break;
        }

        return implode($styler->getNewLine(), $lines).$styler->getNewLine();
    }
}
