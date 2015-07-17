<?php

namespace Dwo\Tracedump\Styler\Coloring;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class ColoringFactory
{
    const CLI = 'Dwo\Tracedump\Styler\Coloring\CliColoring';
    const WIN = 'Dwo\Tracedump\Styler\Coloring\WinColoring';
    const NONE = 'Dwo\Tracedump\Styler\Coloring\NoneColoring';
    const HTML = 'Dwo\Tracedump\Styler\Coloring\HtmlColoring';

    const SCHEMA_CLI = 'Dwo\Tracedump\Styler\Schema\CliSchema';
    const SCHEMA_BROWSER = 'Dwo\Tracedump\Styler\Schema\BrowserSchema';

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
