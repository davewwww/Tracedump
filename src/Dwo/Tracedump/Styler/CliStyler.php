<?php

namespace Dwo\Tracedump\Styler;

use Symfony\Component\VarDumper\VarDumper;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class CliStyler extends DefaultStyler implements StylerInterface
{
    /**
     * @return string
     */
    public function getWhitespace()
    {
        return ' ';
    }

    /**
     * @return string
     */
    public function getNewLine()
    {
        return PHP_EOL;
    }

    /**
     * @return string
     */
    public function getSeperator()
    {
        $newLine = $this->getNewLine();

        $line = str_repeat('-', 40);
        $line = $this->style('gray', $line);

        return $newLine.$line.$newLine;
    }

    /**
     * @param array $dumps
     *
     * @return string
     */
    public function dump(array $dumps)
    {
        $newLine = $this->getNewLine();
        $dump = implode($this->getSeperator(), $dumps).$this->getNewLine();

        return $dump.$newLine.$newLine;
    }
}
