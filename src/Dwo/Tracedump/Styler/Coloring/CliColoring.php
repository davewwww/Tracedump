<?php

namespace Dwo\Tracedump\Styler\Coloring;

use Symfony\Component\VarDumper\VarDumper;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class CliColoring extends AbstractColoring
{
    const NO_COLOR = 0;

    /*
        http://www.developer.com/open/article.php/10930_631241_2/Linux-Console-Colors--Other-Tricks.htm
        https://wiki.archlinux.org/index.php/Color_Bash_Prompt

        TextFarbe
        \033[XXm

        BackgroundFarbe
        \033[XX;YYm

        0 = default colour
        1 = bold
        4 = underlined
        5 = flashing text
        7 = reverse field
        31 = red
        32 = green
        33 = orange
        34 = blue
        35 = purple
        36 = cyan
        37 = grey
        40 = black background
        41 = red background
        42 = green background
        43 = orange background
        44 = blue background
        45 = purple background
        46 = cyan background
        47 = grey background
        90 = dark grey
        91 = light red
        92 = light green
        93 = yellow
        94 = light blue
        95 = light purple
        96 = turquoise
        100 = dark grey background
        101 = light red background
        102 = light green background
        103 = yellow background
        104 = light blue background
        105 = light purple background
        106 = turquoise background

        die("\033[7m" . "Blabla". "\033[0m");
    */

    /**
     * @return string
     */
    public function __toString()
    {
        if (null === $command = $this->buildCommand()) {
            $command = self::NO_COLOR;

        }

        return "\033[".$command."m".$this->text."\033[0m";
    }


    /**
     * @return string
     */
    private function buildCommand()
    {
        $allFontColors = $this->getTextColorTable();
        $allBgColors = $this->getBGColorTable();
        $allStyles = $this->getStyleTable();

        $styles = [];
        $textColor = $bgColor = null;

        //textColor
        if (null !== $this->textColor && isset($allFontColors[$this->textColor])) {
            $textColor = $allFontColors[$this->textColor];
        }

        //backgroundColor
        if (null !== $this->backgroundColor && isset($allBgColors[$this->backgroundColor])) {
            $bgColor = $allBgColors[$this->backgroundColor];

            if (null === $textColor) {
                $textColor = self::NO_COLOR;
            }
        }

        //Style
        if (null !== $this->style) {
            foreach (array_unique($this->style) as $style) {
                if (isset($allStyles[$style])) {
                    $styles[] = $allStyles[$style];
                }
            }
        }

        if (null !== $textColor) {
            $styles[] = $textColor;
        }
        if (null !== $bgColor) {
            $styles[] = $bgColor;
        }

        return implode(';', $styles);
    }

    /**
     * @return array
     */
    private function getTextColorTable()
    {
        return array(
            ColoringInterface::NO_COLOR    => self::NO_COLOR,
            ColoringInterface::RED         => 31,
            ColoringInterface::LIGHT_GREEN => 92,
            ColoringInterface::YELLOW      => 93,
            ColoringInterface::GRAY        => 90,#30
        );
    }

    /**
     * @return array
     */
    private function getBGColorTable()
    {
        return array(
            ColoringInterface::RED        => 41,
            ColoringInterface::GREEN      => 42,
            ColoringInterface::BLUE       => 44,
            ColoringInterface::LIGHT_BLUE => 104,
        );
    }

    /**
     * @return array
     */
    private function getStyleTable()
    {
        return array(
            ColoringInterface::STYLE_BOLD     => 1,
            ColoringInterface::STYLE_INVERSED => 7,
        );
    }
}
