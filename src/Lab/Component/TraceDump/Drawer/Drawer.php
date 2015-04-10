<?php

namespace Lab\Component\TraceDump\Drawer;

use Lab\Component\TraceDump\Styler\StylerInterface;

/**
 * @author David Wolter <david@dampfer.net>
 */
class Drawer
{
    /**
     * @var StylerInterface
     */
    protected $styler;

    /**
     * @param StylerInterface $styler
     */
    public function __construct(StylerInterface $styler)
    {
        $this->styler = $styler;
    }

    /**
     * @param array $dump
     *
     * @return string
     */
    public function draw(array $dump)
    {
        $type = $dump["type"];
        $value = $dump["value"];

        switch ($type) {
            case "array":
                $drawer = new ArrayDrawer($this->styler);
                $lines = $drawer->draw($value);
                break;

            case "object":
                $drawer = new ObjectDrawer($this->styler);
                $lines = $drawer->draw($value, 0, 4);
                break;

            default:
                $value = $this->styler->style($type, $value);
                $lines = array($value);
                break;
        }

        return implode($this->styler->getNewLine(), $lines).$this->styler->getNewLine();
    }

}
