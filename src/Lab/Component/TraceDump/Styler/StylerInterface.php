<?php

namespace Lab\Component\TraceDump\Styler;


/**
 * @author David Wolter <david@dampfer.net>
 */
interface StylerInterface
{
    function style($type, $value);
    function getWhitespace();
    function getNewLine();
}
