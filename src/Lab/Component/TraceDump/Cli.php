<?php

namespace Lab\Component\TraceDump;

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
use Lab\Component\TraceDump\Drawer\ArrayDrawer;
use Lab\Component\TraceDump\Drawer\ObjectDrawer;
use Lab\Component\TraceDump\Styler\CliStyler;
use Lab\Component\TraceDump\Styler\StylerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 *
 * @author David Wolter <david@dampfer.net>
 */
class Cli
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

    function tracedump()
    {
        $dumps = array();
        $args = func_get_args();

        foreach ($args as $arg) {
            $dump = Dumper::dump($arg);
            $dumpOutput = $this ->draw($dump);

            $dumps[] = $dumpOutput;
        }

        return implode("\n\n".$this->LCC(str_repeat("-", 40), 90)."\n", $dumps)."\n\n";
    }

    /**
     * @param array $dump
     *
     * @return string
     */
    function draw(array $dump)
    {
        $type = $dump["type"];
        $value = $dump["value"];

        switch ($type) {
            case "array":
                $drawer = new ArrayDrawer($this->styler);
                $lines = $drawer ->draw($value);
                break;

            case "object":
                $drawer = new ObjectDrawer($this->styler);
                $lines = $drawer ->draw($value, 0, 4);
                break;

            default:
                $value = $this->styler->style($type, $value);
                $lines = array($value);
                break;
        }

        return implode($this->styler->getNewLine(), $lines).$this->styler->getNewLine();
    }

    /**
     * @param object $object
     *
     * @return array
     */
    function dumpObj($object)
    {
        return array(
            "type"       => "object",
            "object"     => $object,
            "reflection" => new \ReflectionClass($object),
        );

        $refl->
        $return = array(
            "type" => "object",
            "name" => $refl->getName(),
        );

        if ($reflectionProperty = $refl->getProperties()) {
            $return["properties"] = $this->dumpProperties($reflectionProperty, $object, $refl);
        }
        if ($reflectionProperty = $refl->getStaticProperties()) {
            $return["properties_static"] = $this->dumpProperties($reflectionProperty, $object, $refl);
        }
        if ($reflectionMethod = $refl->getMethods()) {
            $return["methods"] = $this->dumpMethods($reflectionMethod, $object, $refl);
        }

        return $return;
    }

    function dumpScalar($value)
    {
        switch ($type = gettype($value)) {
            case "string":
                $l = strlen($value);
                $max = 100;
                if ($l > $max) {
                    $value = substr($value, 0, $max)." ... ";
                }
                break;

            case "boolean":
                $value = $value ? "true" : "false";
                break;

            case "NULL":
                $value = "null";
                break;
            default:
                $value = $type;
                break;
        }

        return array(
            "type"  => $type,
            "value" => $value,
        );
    }

    /**
     * @deprecated
     *
     * @param ReflectionMethod[] $reflectionMethods
     * @param null               $class
     * @param null               $refl
     *
     * @return array
     */
    function dumpMethods($reflectionMethods, $class = null, $refl = null)
    {
        $end = array();

        $className = $refl->getName();

        foreach ($reflectionMethods as $k => $method) {

            //Method Parameter
            $ar_param = array();
            if ($param = $method ->getParameters()) {
                foreach ($param as $vv) {
                    $ar_param[] = $vv->getName();
                }
            }

            //extends
            $extendsClass = $method ->getDeclaringClass();
            if ($className == $extendsClass ->getName()) {
                $extendsClass = "";
            }
            if ($extendsClass) {
                $extendsClass = $extendsClass->getName();
            }

            $end[] = array(
                "type"      => "method",
                "access"    => $this->dumpAccess($method),
                "name"      => $method ->getName(),
                "arguments" => $ar_param,
                #"header"	=> "<b>Methods</b>" . ($extendsClass ? " extends ". $extendsClass : ""),
                "declaring" => $extendsClass,
            );
        }

        return $end;
    }

    /**
     * @deprecated
     *
     * @param ReflectionProperty[] $reflectionProperties
     * @param object               $object
     * @param ReflectionClass      $refl
     *
     * @return array
     */
    function dumpProperties(array $reflectionProperties, $object = null, ReflectionClass $refl = null)
    {
        $end = array();

        $className = $refl->getName();

        foreach ($reflectionProperties as $property) {

            //Name
            $name = $property ->getName();

            //Value
            $value = null;
            if (method_exists($property, "setAccessible")) {
                $property ->setAccessible(true);
            }
            if (method_exists($property, "getValue")) {
                $value = $property ->getValue($object);
            }

            //extends
            $extendsClass = null;
            if (method_exists($property, "getDeclaringClass")) {
                $extendsClass = $property ->getDeclaringClass();
            }
            if ($extendsClass) {
                if ($className == $extendsClass ->getName()) {
                    $extendsClass = "";
                }
            }
            if ($extendsClass) {
                $extendsClass = $extendsClass->getName();
            }

            $end[] = array(
                "value"  => $this->dumpScalar($value),
                "name"   => $name,
                "type"   => gettype($value),
                "access" => $this->dumpAccess($property),
                "header" => $extendsClass,
            );
        }

        return $end;
    }

    /**
     *
     * @deprecated
     */
    function dumpAccess($ref)
    {
        $ar = array("static", "public", "procted", "protected", "private", "abstract", "abstract",);
        foreach ($ar as $fe) {
            $method = "is".ucfirst($fe);
            if (method_exists($ref, $method)) {
                if ($ref ->$method()) {
                    #if($fe != "protected")
                    #	$fe .= "\t";

                    return $fe;
                }
            }
        }

        return "unknown";
    }

    /**
     * @deprecated
     */
    function dietable($data)
    {

        $trs = "";
        $ar_trs = array();

        foreach ($data as $fe) {
            $type = isset($fe["type"]) ? $fe["type"] : null;
            $prop = isset($fe["access"]) ? $fe["access"] : null;
            $name = isset($fe["name"]) ? $fe["name"] : null;
            $value = isset($fe["value"]) ? $fe["value"] : null;
            $header = isset($fe["header"]) ? $fe["header"] : null;

            if ($value) {
                if ($type == "array") {
                    $name = $name."\t".trim($value);
                } else {
                    $name =
                    $name = $name."\t= ".trim($value);
                }

            }

            $line = ($prop ? $prop."\t" : "").
                $name.
                "\n";

            if ($header) {
                if (!isset($ar_trs[$header])) {
                    $ar_trs[$header] = "";
                }

                $ar_trs[$header] .= $line;
            } else {
                $trs .= $line;
            }
        }

        if (count($ar_trs)) {
            foreach ($ar_trs as $header => $fe_trs) {
                $trs .= $header."\n".$fe_trs."\n";
            }
        }

        return "\n".$trs."\n";
    }

    /**
     * @deprecated
     */
    function LCC($text, $command = "")
    {

        return "\033[".$command."m".$text."\033[0m";
    }
}