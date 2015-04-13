<?php

namespace Lab\Component\TraceDump;

use Lab\Component\TraceDump\Drawer\ArrayDrawer;
use Lab\Component\TraceDump\Drawer\ObjectDrawer;
use Lab\Component\TraceDump\Styler\StylerInterface;

/**
 * @author David Wolter <david@dampfer.net>
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
