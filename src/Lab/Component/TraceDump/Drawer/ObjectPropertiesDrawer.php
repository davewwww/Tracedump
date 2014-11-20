<?php

namespace Lab\Component\TraceDump\Drawer;

use Lab\Component\TraceDump\Styler\StylerInterface;
use ReflectionMethod;

/**
 * @author David Wolter <david@dampfer.net>
 */
class ObjectPropertiesDrawer
{
    const IDENTS = 4;

    /**
     * @var StylerInterface
     */
    protected $styler;

    /**
     * @param StylerInterface $styler
     */
    function __construct(StylerInterface $styler)
    {
        $this->arrayDrawer = new ArrayDrawer($styler);
        $this->styler = $styler;
    }

    /**
     * @param array $data
     * @param int   $deep
     *
     * @return mixed
     */
    function draw(array $data, $deep = 0, $pos = 0)
    {
        $object = $data["object"];
        $properties = $data["properties"];

        $declaringClassPre = null;

        $lines = [];

        foreach ($properties as $property) {

            $access = $property["access"];
            $name = $property["name"];
            $value = $property["value"];
            $declaringClass = $property["declaringClass"];

            $ident = str_repeat($this->styler->getWhitespace(), self::IDENTS * $deep);

            $whitespacesAccess = "";
            if (($whitespacesCount = 10 - strlen($access)) > 0) {
                $whitespacesAccess = str_repeat($this->styler->getWhitespace(), $whitespacesCount);
            }
            $access = " ".$access." ";
            $name = "$".$name;

            if (is_array($value)) {
                $value = $this->arrayDrawer->draw($value, $deep + 2, $pos );
                $value = implode($this->styler->getNewLine(), $value);
            } else {
                $value = $this->styler->style(gettype($value), $value);
            }

            $lines[] = $ident .
                #$whitespacesAccess.
                $this->styler->style("gray", $access).
                " ".
                $this->styler->style("name", $name).
                " = ".
                $value;

        }

        if($pos>0) {
            foreach($lines as $k => $v) {
                $lines[$k] = str_repeat($this->styler->getWhitespace(), $pos).$v;
            }
        }
        return $lines;
    }

}
