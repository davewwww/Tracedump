<?php

namespace Dwo\Tracedump\Styler\Coloring;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
interface ColoringInterface
{
    const NO_COLOR = 'no_color';
    const BLACK = 'black';

    const RED = 'red';
    const GREEN = 'green';
    const BLUE = 'blue';
    const DARK_BLUE = 'dark_blue';
    const YELLOW = 'yellow';
    const GRAY = 'gray';
    const LIGHT_GREEN = 'light_green';
    const LIGHT_BLUE = 'light_blue';

    const STYLE_BOLD = 'bold';
    const STYLE_INVERSED = 'inversed';

    /**
     * @return string
     */
    public function __toString();

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

    /**
     * @param string $schema
     *
     * @return string
     */
    public function getColorFromSchema($schema);
}
