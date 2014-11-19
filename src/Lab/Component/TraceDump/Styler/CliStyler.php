<?php

namespace Lab\Component\TraceDump\Styler;

/**
 * @author David Wolter <david@dampfer.net>
 */
class CliStyler implements StylerInterface
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
                $value = $refl->getName();
                break;

            default:
                $value = $this->LCC("(".$type.")", 5);
                break;
        }

        return $value;
    }

    function LCC($text, $command = "")
    {

        return "\033[".$command."m".$text."\033[0m";
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
