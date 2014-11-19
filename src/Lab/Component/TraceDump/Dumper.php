<?php

namespace Lab\Component\TraceDump;

/**
 *
 * @author David Wolter <david@dampfer.net>
 */
class Dumper
{
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public static function dump($data)
    {
        switch ($type = gettype($data)) {
            case "array":
                $value = self::dumpArray($data);
                break;

            case "object":
                $value = self::dumpObject($data);
                break;

            case "resource":
                $value = "Ressource: ".get_resource_type($data);
                break;

            case "string":
                $value = $data;
                break;

            default:
                $value = var_export($data, 1);
                break;
        }

        return array(
            "type"  => $type,
            "value" => $value,
        );
    }

    /**
     * @param array $array
     *
     * @return array
     */
    private static function dumpArray($array)
    {
        $end = array();
        foreach ($array as $name => $value) {
            $end[] = array(
                "name"  => $name,
                "value" => is_array($value) ? self::dumpArray($value) : self::dumpValue($value),
                "type"  => gettype($value),
            );
        }

        return $end;
    }

    /**
     * @param object $object
     *
     * @return array
     */
    private static function dumpObject($object)
    {
        return array(
            "type"       => "object",
            "object"     => $object,
            "reflection" => new \ReflectionClass($object),
        );
    }

    /**
     * @param mixed $value
     *
     * @return array
     */
    private static function dumpValue($value)
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

            case "object":
                $refl = new \ReflectionClass($value);
                #$value = $refl -> getName();
                $value = $refl->getName();
                break;

            case "NULL":
                $value = "null";
                break;
        }

        return $value;
    }
}