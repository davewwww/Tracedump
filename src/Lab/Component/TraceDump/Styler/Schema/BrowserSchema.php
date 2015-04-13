<?php

namespace Lab\Component\TraceDump\Styler\Schema;

use Lab\Component\TraceDump\Styler\Coloring\ColoringInterface;

/**
 * @author David Wolter <david@dampfer.net>
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
