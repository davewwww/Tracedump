<?php

namespace Lab\Component\TraceDump\Styler\Coloring;

/**
 * @author David Wolter <david@dampfer.net>
 */
class ColoringFactory
{
    const CLI = 'Lab\Component\TraceDump\Styler\Coloring\CliColoring';
    const WIN = 'Lab\Component\TraceDump\Styler\Coloring\WinColoring';
    const NONE = 'Lab\Component\TraceDump\Styler\Coloring\NoneColoring';

    /**
     * @var ColoringInterface
     */
    static protected $coloringClass;

    /**
     * @param $text
     *
     * @return ColoringInterface
     */
    public static function create($text)
    {
        if (null === self::$coloringClass) {
            throw new \RuntimeException('ColoringFactory needs a ColoringInterface');
        }

        return new self::$coloringClass($text);
    }

    /**
     * @param string $coloringClass
     */
    public static function setColoringClass($coloringClass)
    {
        self::$coloringClass = $coloringClass;
    }

}
