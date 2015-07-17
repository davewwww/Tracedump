<?php

namespace Dwo\Tracedump\Styler\Schema;

use Dwo\Tracedump\Styler\Coloring\ColoringInterface;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class BrowserSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getAll()
    {
        return array(
            self::STRING           => ColoringInterface::RED,
            self::INTEGER          => '#2E8B57',
            self::BOOLEAN_TRUE     => ColoringInterface::GREEN,
            self::BOOLEAN_FALSE    => ColoringInterface::RED,
            self::OBJECT           => ColoringInterface::BLUE,
            self::OBJECT_SUB       => ColoringInterface::BLACK,
            self::VARIABLE         => ColoringInterface::BLUE,
            self::NONE             => ColoringInterface::NO_COLOR,
            self::KEYWORD_FUNCTION => ColoringInterface::DARK_BLUE,
            self::METHOD           => ColoringInterface::BLUE,
        );
    }
}
