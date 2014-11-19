<?php

namespace Lab\Component\TraceDump\Styler;

/**
 * @author David Wolter <david@dampfer.net>
 */
class CliStyler implements StylerInterface
{

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
                list($name,$param) = $value;
                $value = "function ";
                $value .= $this->LCC($name, 1);
                $value .= "(".$this->LCC($param, 93).")";
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
