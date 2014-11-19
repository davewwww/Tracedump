<?php

namespace Lab\Component\TraceDump\Drawer;

use Lab\Component\TraceDump\Styler\StylerInterface;

/**
 * @author David Wolter <david@dampfer.net>
 */
class ObjectDrawer
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
    function __construct(StylerInterface $styler)
    {
        $this->styler = $styler;
        $this->methodDrawer = new MethodDrawer($styler);
    }

    function draw(array $data, $deep = 0)
    {
        $object = $data["object"];
        $reflection = $data["reflection"];

        $lines = array(
            $this->styler->style("object_name", $reflection->getName()),
            $this->styler->getNewLine(),
        );

        $methods = $reflection->getMethods();
        if (!empty($methods)) {
            $methodLines = $this->methodDrawer->draw(array("object"=>$object,"methods" => $methods));
            $lines = array_merge($lines,$methodLines);
        }

         #die(tde($methodLines));

        return $lines;
    }

}
