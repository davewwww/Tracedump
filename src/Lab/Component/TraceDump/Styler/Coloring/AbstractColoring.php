<?php

namespace Lab\Component\TraceDump\Styler\Coloring;

/**
 * @author David Wolter <david@dampfer.net>
 */
abstract class AbstractColoring implements ColoringInterface
{
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
     * @param $text
     */
    public function __construct($text)
    {
        $this->text = $text;
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
