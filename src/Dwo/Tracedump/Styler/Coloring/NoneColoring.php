<?php

namespace Dwo\Tracedump\Styler\Coloring;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class NoneColoring extends AbstractColoring
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->text;
    }
}
