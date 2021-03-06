<?php

namespace Dwo\Tracedump\Drawer;

use Dwo\Tracedump\Styler\StylerInterface;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class ObjectDrawer implements DrawerInterface
{
    const IDENTS = 4;

    /**
     * @var StylerInterface
     */
    protected $styler;

    /**
     * @var MethodDrawer
     */
    protected $methodDrawer;

    /**
     * @param StylerInterface $styler
     */
    public function __construct(StylerInterface $styler)
    {
        $this->styler = $styler;
        $this->methodDrawer = new MethodDrawer($styler);
        $this->propertyDrawer = new ObjectPropertiesDrawer($styler);
    }

    /**
     * {@inheritdoc}
     */
    public function draw(array $data, $deep = 0, $pos = 0)
    {
        $object = $data["object"];
        $reflection = $data["reflection"];
        $methods = $data["methods"];
        $properties = $data["properties"];

        #die(tde($properties));

        $ident = str_repeat($this->styler->getWhitespace(), self::IDENTS * $deep);
        $posWhitespaces = str_repeat($this->styler->getWhitespace(), $pos);

        $lines = array(
            $posWhitespaces.$ident.$this->styler->style("object_name", $reflection->getName()),
            $posWhitespaces.$ident.$this->styler->getNewLine(),
        );

        /**
         * methods & properties anhang der declaringClass groupen
         */
        if (!empty($methods)) {
            $methodLines = $this->methodDrawer->draw(array("object" => $object, "methods" => $methods), $deep, $pos);
            $lines = array_merge($lines, $methodLines);
        }
        if (!empty($properties)) {
            $propertiesLines = $this->propertyDrawer->draw(
                array("object" => $object, "properties" => $properties),
                $deep,
                $pos
            );
            $lines = array_merge($lines, $propertiesLines);
        }

        #die(tde($methodLines));

        return $lines;
    }

}
