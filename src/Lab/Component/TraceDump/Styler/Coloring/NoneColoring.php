<?php

namespace Lab\Component\TraceDump\Styler\Coloring;

/**
 * @author David Wolter <david@dampfer.net>
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
