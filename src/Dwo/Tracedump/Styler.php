<?php

namespace Dwo\Tracedump;

use Dwo\Tracedump\Styler\CliStyler;
use Dwo\Tracedump\Styler\Coloring\ColoringFactory;
use Dwo\Tracedump\Styler\HtmlStyler;
use Dwo\Tracedump\Styler\StylerInterface;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class Styler
{
    /**
     * @var bool
     */
    private static $forceCli = false;

    /**
     * @return StylerInterface
     */
    public static function getStyler()
    {
        if (self::isCli()) {
            ColoringFactory::setColoringClass(self::isWindows() ? ColoringFactory::NONE : ColoringFactory::CLI);
            ColoringFactory::setSchemaClass(ColoringFactory::SCHEMA_CLI);

            return new CliStyler();
        } else {
            ColoringFactory::setColoringClass(ColoringFactory::HTML);
            ColoringFactory::setSchemaClass(ColoringFactory::SCHEMA_BROWSER);

            return new HtmlStyler();
        }
    }

    /**
     * @return bool
     */
    public static function isCli()
    {
        return 'cli' === PHP_SAPI || self::$forceCli;
    }

    /**
     * @return bool
     */
    public static function isWindows()
    {
        return preg_match('/WIN/', PHP_OS);
    }

    /**
     * @param bool $force
     */
    public static function forceCli($force)
    {
        self::$forceCli = $force;
    }
}
