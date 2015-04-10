<?php

namespace Lab\Component\TraceDump;

use Lab\Component\TraceDump\Drawer\Drawer;
use Lab\Component\TraceDump\Dumper\Dumper;
use Lab\Component\TraceDump\Styler\StylerInterface;

/**
 *
 * @author David Wolter <david@dampfer.net>
 */
class Cli implements TraceDumperInterface
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
     * {@inheritdoc}
     */
    public function tracedump()
    {
        $dumps = array();
        $args = func_get_args();

        $drawer = new Drawer($this->styler);

        foreach ($args as $arg) {
            $dumps[] = $drawer->draw(Dumper::dump($arg));
        }

        $line = PHP_EOL.PHP_EOL.$this->styler->style('gray', $this->styler->getLine()).PHP_EOL;

        return implode($line, $dumps).PHP_EOL.PHP_EOL;
    }

}