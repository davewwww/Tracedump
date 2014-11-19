<?php

namespace Lab\Component\TraceDump\Drawer;

use Lab\Component\TraceDump\Styler\StylerInterface;
use ReflectionMethod;

/**
 * @author David Wolter <david@dampfer.net>
 */
class MethodDrawer
{
    const IDENTS = 4;

    /**
     * @var StylerInterface
     */
    protected $styler;

    /**
     * @param StylerInterface $styler
     */
    function __construct(StylerInterface $styler)
    {
        $this->styler = $styler;
    }

    /**
     * @param array $data
     * @param int   $deep
     *
     * @return mixed
     */
    function draw(array $data, $deep = 0)
    {
        $declaringClassPre = null;

        /**
         * @var ReflectionMethod[] $methods
         */
        $methods = $data["methods"];
        $object = $data["object"];

        $lines = [];

        foreach ($methods as $method) {
            $name = $method->getName();

            //Method Parameter
            $arguments = array();
            foreach ($method ->getParameters() as $reflectionParameter) {

                $param = "";

                //Cast
                if (null !== $class = $reflectionParameter->getClass()) {
                    $param .= $this->styler->style("object_name", $class->getShortName())." ";
                } elseif ($reflectionParameter->isArray()) {
                    $param .= "array ";
                }

                //Name
                $param .= "$".$reflectionParameter->getName();

                //DefaultValue
                if ($reflectionParameter->isDefaultValueAvailable()) {
                    $defaultValue = $reflectionParameter->getDefaultValue();
                    if (is_array($defaultValue)) {
                        $defaultValue = str_replace("\n", "", var_export($defaultValue, 1));
                    } else {
                        $defaultValue = $this->styler->style(gettype($defaultValue), $defaultValue);
                    }
                    $param .= " = ".$defaultValue;
                }

                $arguments[] = $param;
            }


            //DeclaringClass
            $declaringClass = $method ->getDeclaringClass()->getName();
            if (get_class($object) !== $declaringClass && $declaringClassPre !== $declaringClass) {
                $lines[] = $this->styler->getWhitespace();
                $lines[] = $this->styler->style("object_name_light", $declaringClassPre = $declaringClass);
            }

            $name = $this->styler->style("method", array($name, $arguments ? implode(", ", $arguments) : ""));

            $lines[] = $name;

            #die(tde($method->getParameters()));
        }

        return $lines;
    }

}
