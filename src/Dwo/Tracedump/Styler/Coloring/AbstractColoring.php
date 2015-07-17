<?php

namespace Dwo\Tracedump\Styler\Coloring;

use Dwo\Tracedump\Styler\Schema\SchemaInterface;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
abstract class AbstractColoring implements ColoringInterface
{
    /**
     * @var SchemaInterface
     */
    protected $schema;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $textColor;

    /**
     * @var string
     */
    protected $backgroundColor;

    /**
     * @var array
     */
    protected $style;

    /**
     * @param string          $text
     * @param SchemaInterface $schema
     */
    public function __construct($text, SchemaInterface $schema)
    {
        $this->text = $text;
        $this->schema = $schema;
    }

    /**
     * {@inheritdoc}
     */
    public function getColorFromSchema($schema)
    {
        $all = $this->schema->getAll();

        return isset($all[$schema]) ? $all[$schema] : ColoringInterface::NO_COLOR;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param string $textColor
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
    }

    /**
     * @param string $backgroundColor
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @param array $style
     */
    public function addStyle($style)
    {
        $this->style[] = $style;
    }
}
