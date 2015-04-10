<?php

namespace Lab\Component\TraceDump\Styler;

/**
 * @author David Wolter <david@dampfer.net>
 */
interface StylerInterface
{
    /**
     * @param string $type
     * @param string $value
     *
     * @return string
     */
    public function style($type, $value);

    /**
     * @return string
     */
    public function getWhitespace();

    /**
     * @return string
     */
    public function getNewLine();

    /**
     * @return string
     */
    public function getLine();
}
