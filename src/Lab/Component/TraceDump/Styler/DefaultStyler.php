<?php

namespace Lab\Component\TraceDump\Styler;

use Lab\Component\TraceDump\Styler\Coloring\ColoringFactory;
use Lab\Component\TraceDump\Styler\Coloring\ColoringInterface;
use Lab\Component\TraceDump\Styler\Schema\SchemaInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @author David Wolter <david@dampfer.net>
 */
abstract class DefaultStyler
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
                $color->setTextColor($color->getColorFromSchema(SchemaInterface::STRING));
                break;

            case 'integer':
            case 'double':
                $color->setTextColor($color->getColorFromSchema(SchemaInterface::INTEGER));
                break;

            case 'boolean':
                $color->setText($value = in_array($value, array(true, 'true'), true) ? 'true' : 'false');
                $color->setBackgroundColor(
                    $color->getColorFromSchema(
                        'true' === $value ? SchemaInterface::BOOLEAN_TRUE : SchemaInterface::BOOLEAN_FALSE
                    )
                );
                break;

            case 'object':
                $refl = new \ReflectionClass($value);
                $color = $this->style('object_name', $refl->getName());
                break;

            case 'object_name':
                $color->addStyle(ColoringInterface::STYLE_BOLD);
                $color->setBackgroundColor($color->getColorFromSchema(SchemaInterface::OBJECT));
                break;

            case 'object_name_light':
                $color->setBackgroundColor($color->getColorFromSchema(SchemaInterface::OBJECT_SUB));
                break;

            case 'method':
                list($name, $param) = $value;

                $nameColor = ColoringFactory::create($name);
                $nameColor->addStyle(ColoringInterface::STYLE_BOLD);

                $color = sprintf('function %s(%s)', (string) $nameColor, $param);
                break;

            case 'name':
                $color->setTextColor($color->getColorFromSchema(SchemaInterface::VARIABLE));
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
                $color->setTextColor($color->getColorFromSchema(SchemaInterface::NONE));
                break;
        }

        return is_object($color) ? (string) $color : $color;
    }
}
