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
    const HTML = 'Lab\Component\TraceDump\Styler\Coloring\HtmlColoring';

    const SCHEMA_CLI = 'Lab\Component\TraceDump\Styler\Schema\CliSchema';
    const SCHEMA_BROWSER = 'Lab\Component\TraceDump\Styler\Schema\BrowserSchema';

    /**
     * @var string
     */
    static protected $coloringClass;

    /**
     * @var string
     */
    static protected $schemaClass;

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

        return new self::$coloringClass($text, new self::$schemaClass());
    }



    /**
     * @param string $coloringClass
     */
    public static function setColoringClass($coloringClass)
    {
        self::$coloringClass = $coloringClass;
    }

    /**
     * @param string $schemaClass
     */
    public static function setSchemaClass($schemaClass)
    {
        self::$schemaClass = $schemaClass;
    }

}
