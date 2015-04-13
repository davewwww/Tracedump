<?php

namespace Lab\Component\TraceDump\Styler\Coloring;

use Symfony\Component\VarDumper\VarDumper;

/**
 * @author David Wolter <david@dampfer.net>
 */
class HtmlColoring extends AbstractColoring
{
    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        try {
            $css = $this->buildCss();
        } catch (\Exception $e) {
            VarDumper::dump($e);
            die();
        }

        return sprintf('<span style="%s">%s</span>', implode(';', $css), $this->text);
    }

    /**
     * @return array
     */
    private function buildCss()
    {
        $allColors = $this->getColorTable();

        $css = [];
        $textColor = $bgColor = null;

        //textColor
        if (null !== $this->textColor) {
            $textColor = isset($allColors[$this->textColor]) ? $allColors[$this->textColor] : $this->textColor;
        }

        //backgroundColor
        if (null !== $this->backgroundColor) {
            $bgColor = isset($allColors[$this->backgroundColor]) ? $allColors[$this->backgroundColor] : $this->backgroundColor;

            if (null === $textColor) {
                $textColor = ColoringInterface::NO_COLOR;
            }
        }

        //Style
        if (null !== $this->style) {
            //bold
            if (in_array(ColoringInterface::STYLE_BOLD, $this->style)) {
                $css[] = 'font-weight:bold';
            }
        }

        if (null !== $textColor) {
            $css[] = sprintf('color:%s', $textColor);
        }
        if (null !== $bgColor) {
            $css[] = sprintf('background-color:%s', $textColor);
        }

        return $css;
    }

    /**
     * @return array
     */
    private function getColorTable()
    {
        return array(
            ColoringInterface::NO_COLOR    => 'black',
            ColoringInterface::LIGHT_GREEN => 'lightGreen',
            ColoringInterface::LIGHT_BLUE  => 'lightBlue',
            ColoringInterface::DARK_BLUE   => 'darkBlue',
        );
    }
}
