<?php

namespace Lab\Component\TraceDump\Styler;

use Lab\Component\TraceDump\Styler\Coloring\ColoringFactory;
use Lab\Component\TraceDump\Styler\Coloring\ColoringInterface;
use Lab\Component\TraceDump\Styler\Schema\SchemaInterface;

/**
 * @author David Wolter <david@dampfer.net>
 */
class HtmlStyler extends DefaultStyler implements StylerInterface
{
    /**
     * {@inheritdoc}
     */
    public function style($type, $value)
    {
        $color = ColoringFactory::create(is_scalar($value) ? $value : '');

        switch ($type) {

            case 'object':
                $refl = new \ReflectionClass($value);
                $color = $this->style('object_name', $refl->getName());
                break;

            case 'object_name':
                $color->addStyle(ColoringInterface::STYLE_BOLD);
                $color->setTextColor($color->getColorFromSchema(SchemaInterface::OBJECT));
                break;

            case 'object_name_light':
                $color->setTextColor($color->getColorFromSchema(SchemaInterface::OBJECT_SUB));
                break;

            case 'method':
                list($name, $param) = $value;

                $nameColor = ColoringFactory::create($name);
                $nameColor->addStyle(ColoringInterface::STYLE_BOLD);
                $nameColor->setTextColor($color->getColorFromSchema(SchemaInterface::METHOD));

                $textFunction = ColoringFactory::create('function');
                $textFunction->setTextColor($color->getColorFromSchema(SchemaInterface::KEYWORD_FUNCTION));
                $textFunction->addStyle(ColoringInterface::STYLE_BOLD);

                $color = sprintf('%s %s(%s)', (string) $textFunction, (string) $nameColor, $param);
                break;

            default:
                $color = parent::style($type, $value);
                break;
        }

        return is_object($color) ? (string) $color : $color;
    }


    /**
     * @return string
     */
    public function getWhitespace()
    {
        return '&nbsp;';
    }

    /**
     * @return string
     */
    public function getNewLine()
    {
        return '</br>';
    }

    /**
     * @return string
     */
    public function getSeperator()
    {
        return '</br><hr></br>';
    }

    /**
     * @param array $dumps
     *
     * @return string
     */
    public function dump(array $dumps)
    {
        $dump = implode($this->getSeperator(), $dumps).$this->getNewLine();
        $style = <<<EOF
.tracedump {
    font-family: courier;
    font-size:11px;
}

EOF;
        return '<style>'. $style .'</style><div class="tracedump">'. $dump .'</div>';
    }
}
