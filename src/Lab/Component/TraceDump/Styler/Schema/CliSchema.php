<?php

namespace Lab\Component\TraceDump\Styler\Schema;

use Lab\Component\TraceDump\Styler\Coloring\ColoringInterface;

/**
 * @author David Wolter <david@dampfer.net>
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
