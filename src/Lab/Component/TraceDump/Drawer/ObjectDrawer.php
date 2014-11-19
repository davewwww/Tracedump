<?php

namespace Lab\Component\TraceDump\Drawer;

use Lab\Component\TraceDump\Styler\StylerInterface;

/**
 * @author David Wolter <david@dampfer.net>
 */
class ObjectDrawer
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
        $this->styler = $styler;
    }

    function draw(array $data, $deep = 0)
    {
        return array();

        $styler = $this->styler;

        $ident = str_repeat($styler->getWhitespace(), self::IDENTS * $deep);
        $identKey = str_repeat($styler->getWhitespace(), self::IDENTS * ($deep + 1));

        $lines = array(
            "array("
        );

        //maxname
        $nameMaxStrlen = 0;
        foreach ($data as $dump) {
            if (($strlen = strlen($dump["name"])) > $nameMaxStrlen) {
                $nameMaxStrlen = $strlen;
            };
        }

        if (!empty($data)) {
            foreach ($data as $dump) {
                $type = $dump["type"];
                $key = $dump["name"];
                $value = $dump["value"];

                $whitespaces = "";
                if (($whitespacesCount = $nameMaxStrlen - strlen($key)) > 0) {
                    $whitespaces = str_repeat($styler->getWhitespace(), $whitespacesCount);
                }

                $key = $identKey.$styler->style("string", $key).$whitespaces;

                if (is_array($value)) {
                    $drawArray = $this->draw($value, $deep + 1);

                    $lines[] = $key." => ".$drawArray[0];
                    unset($drawArray[0]);

                    $lines = array_merge($lines, $drawArray);

                } elseif (is_scalar($value)) {
                    $lines[] = $key." => ".$styler->style($type, $value);
                }
            }
            $lines[] = $ident.")";
        } else {
            $lines[0] .= ")";
        }

        return $lines;
    }

}
