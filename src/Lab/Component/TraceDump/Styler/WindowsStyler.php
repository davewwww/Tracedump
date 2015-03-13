<?php

namespace Lab\Component\TraceDump\Styler;

/**
 * http://stackoverflow.com/questions/2048509/how-to-echo-with-different-colors-in-the-windows-command-line
 *
 * @author David Wolter <david@dampfer.net>
 */
class WindowsStyler implements StylerInterface
{

    function style($type, $value)
    {

        switch ($type) {
            case "string":
                $l = strlen($value);
                $max = 100;
                if ($l > $max) {
                    $value = substr($value, 0, $max)." ... ";
                }
                $value = $this->LCC("\"".$value."\"", 31);
                break;

            case "integer":
            case "double":
                $value = $this->LCC($value, 92);
                break;

            case "boolean":

                $value = $value === true || $value === "true" ? "true" : "false";
                $color = $value == "true" ? "1;42" : "1;41";
                $value = $this->LCC($value, $color);
                break;

            case "NULL":
                $value = $this->LCC("null", 5);
                break;

            case "object":
                $refl = new \ReflectionClass($value);
                #$value = $refl -> getName();
                $value = $this->style("object_name", $refl->getName());
                break;

            case "object_name":
                $value = $this->LCC($value, "1;44");
                break;

            case "object_name_light":
                $value = $this->LCC($value, "0;104");
                break;

            case "method":
                list($name, $param) = $value;
                $value = "function ";
                $value .= $this->LCC($name, 1);
                $value .= "(".$this->style("name", $param).")";
                break;

            case "name":
                $value = $this->LCC($value, 93);
                break;

            case "gray":
                $value = $this->LCC($value, 7);
                break;

            case "no_style":
                $value = $this->LCC($value, 0);
                break;

            default:
                $value = $this->LCC("(".$type.")", 5);
                break;
        }

        return $value;
    }

    function LCC($text, $command = "")
    {
        return $text;
    }

    function getWhitespace()
    {
        return " ";
    }

    function getNewLine()
    {
        return "\n";
    }
}
