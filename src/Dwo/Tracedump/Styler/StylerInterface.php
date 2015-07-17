<?php

namespace Dwo\Tracedump\Styler;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
interface StylerInterface
{
    /**
     * @param array $dumps
     *
     * @return string
     */
    public function dump(array $dumps);

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
    public function getSeperator();
}
