<?php

namespace Dwo\Tracedump\Styler\Schema;

use Dwo\Tracedump\Styler\Coloring\ColoringInterface;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class CliSchema implements SchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getAll()
    {
        return array(
            self::STRING        => ColoringInterface::RED,
            self::INTEGER       => ColoringInterface::LIGHT_GREEN,
            self::BOOLEAN_TRUE  => ColoringInterface::GREEN,
            self::BOOLEAN_FALSE => ColoringInterface::RED,
            self::OBJECT        => ColoringInterface::BLUE,
            self::OBJECT_SUB    => ColoringInterface::LIGHT_BLUE,
            self::VARIABLE      => ColoringInterface::YELLOW,
            self::NONE          => ColoringInterface::NO_COLOR,
        );
    }
}
