<?php

namespace Lab\Component\TraceDump\Styler;

use Lab\Component\TraceDump\Styler\Coloring\ColoringFactory;
use Lab\Component\TraceDump\Styler\Coloring\ColoringInterface;

/**
 * @author David Wolter <david@dampfer.net>
 */
class CliStyler implements StylerInterface
{
    /**
     * {@inheritdoc}
     */
    public function style($type, $value)
    {
        $color = ColoringFactory::create(is_scalar($value) ? $value : '');

        switch ($type) {
            case 'string':
                $l = strlen($value);
                $max = 100;
                if ($l > $max) {
                    $value = substr($value, 0, $max).' ... ';
                }
                $color->setText('"'.$value.'"');
                $color->setTextColor(ColoringInterface::RED);
                break;

            case 'integer':
            case 'double':
                $color->setTextColor(ColoringInterface::LIGHT_GREEN);
                break;

            case 'boolean':
                $color->setText($value = in_array($value, array(true, 'true'), true) ? 'true' : 'false');
                $color->setBackgroundColor('true' === $value ? ColoringInterface::GREEN : ColoringInterface::RED);
                break;

            case 'object':
                $refl = new \ReflectionClass($value);
                $color = $this->style('object_name', $refl->getName());
                break;

            case 'object_name':
                $color->addStyle(ColoringInterface::STYLE_BOLD);
                $color->setBackgroundColor(ColoringInterface::BLUE);
                break;

            case 'object_name_light':
                $color->setBackgroundColor(ColoringInterface::LIGHT_BLUE);
                break;

            case 'method':
                list($name, $param) = $value;

                $nameColor = ColoringFactory::create($name);
                $nameColor->addStyle(ColoringInterface::STYLE_BOLD);

                $color = sprintf('function %s(%s)', (string) $nameColor, $param);
                break;

            case 'name':
                $color->setTextColor(ColoringInterface::YELLOW);
                break;

            case 'gray':
                $color->setTextColor(ColoringInterface::GRAY);
                break;

            case 'NULL':
                $color->setText('null');
                $color->addStyle(ColoringInterface::STYLE_INVERSED);
                break;

            default:
            case 'no_style':
                $color->setTextColor(ColoringInterface::NO_COLOR);
                break;
        }

        return is_object($color) ? (string) $color : $color;
    }

    /**
     * @return string
     */
    public function getWhitespace()
    {
        return ' ';
    }

    /**
     * @return string
     */
    public function getNewLine()
    {
        return PHP_EOL;
    }

    /**
     * @return string
     */
    public function getLine()
    {
        return str_repeat('-', 40);
    }
}
