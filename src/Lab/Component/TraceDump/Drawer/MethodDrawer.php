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
    function draw(array $data, $deep = 0, $pos = 0)
    {
        $object = $data["object"];
        $methods = $data["methods"];

        $ident = str_repeat($this->styler->getWhitespace(), self::IDENTS * $deep);

        $declaringClassPre = null;

        $lines = [];

        foreach ($methods as $method) {
            $name = $method["name"];
            $declaringClass = $method["declaringClass"];

            //Method Parameter
            $arguments = array();
            foreach ($method["arguments"] as $argument) {

                $argumentString = "";

                //Cast
                $cast = $argument["cast"];
                $castType = $argument["castType"];
                if ("object" === $castType) {
                    $argumentString .= $this->styler->style("object_name", $cast)." ";
                } elseif ("array" === $castType) {
                    $argumentString .= "array ";
                }

                //Name
                $argumentString .= "$".$argument["name"];

                //DefaultValue
                $defaultValue = $argument["defaultValue"];
                $defaultValueType = $argument["defaultValueType"];
                if (null !== $defaultValue || "NULL" === $defaultValueType) {
                    if ("array" === $defaultValueType) {
                        $defaultValue = str_replace("\n", "", var_export($defaultValue, 1));
                    } else {
                        $defaultValue = $this->styler->style($defaultValueType, $defaultValue);
                    }
                    $argumentString .= " = ".$defaultValue;
                }

                $arguments[] = $argumentString;
            }

            //DeclaringClass
            if ($declaringClassPre !== $declaringClass) {
                $lines[] = $ident.$this->styler->getWhitespace();
                $lines[] = $ident.$this->styler->style("object_name_light", $declaringClassPre = $declaringClass);
            }

            $access = $method["access"];
            $whitespacesAccess = "";
            if (($whitespacesCount = 10 - strlen($access)) > 0) {
                $whitespacesAccess = str_repeat($this->styler->getWhitespace(), $whitespacesCount);
            }
            $access = " ".$access." ";

            $lines[] = $ident.
                $this->styler->style("gray", $access).
                " ".
                $this->styler->style("method", array($name, $arguments ? implode(", ", $arguments) : ""));
        }

        if($pos>0) {
            foreach($lines as $k => $v) {
                $lines[$k] = str_repeat($this->styler->getWhitespace(), $pos).$v;
            }
        }
        return $lines;
    }

}