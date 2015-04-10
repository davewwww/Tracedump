<?php

namespace Lab\Component\TraceDump\Styler\Coloring;

/**
 * @author David Wolter <david@dampfer.net>
 */
interface ColoringInterface
{
    const NO_COLOR = 'no_color';

    const RED = 'red';
    const GREEN = 'green';
    const BLUE = 'blue';
    const YELLOW = 'yellow';
    const GRAY = 'gray';
    const LIGHT_GREEN = 'light_green';
    const LIGHT_BLUE = 'light_blue';

    const STYLE_BOLD = 'bold';
    const STYLE_INVERSED = 'inversed';

    /**
     * @param mixed $text
     */
    public function setText($text);

    /**
     * @param string $textColor
     */
    public function setTextColor($textColor);

    /**
     * @param string $backgroundColor
     */
    public function setBackgroundColor($backgroundColor);

    /**
     * @param array $style
     */
    public function addStyle($style);
}
