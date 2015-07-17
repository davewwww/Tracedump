<?php

namespace Dwo\Tracedump\Dumper;

use ReflectionProperty;

/**
 * @author Dave Www <davewwwo@gmail.com>
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

            $valueType = gettype($value);

            if (is_array($value)) {
                $value = self::dumpArray($value);
            }
//            elseif(is_object($value)) {
//                $value = self::dumpObject($value);
//            }
            else {
                $value = self::dumpValue($value);
            }

            $end[] = array(
                "name"  => $name,
                "value" => $value,
                "type"  => $valueType,
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
        $reflection = new \ReflectionClass($object);

        return array(
            "type"       => "object",
            "object"     => $object,
            "methods"    => self::dumpMethods($reflection, $object),
            "properties" => self::dumpProperties($reflection, $object),
            "reflection" => new \ReflectionClass($object),
        );
    }

    /**
     * @param \ReflectionClass $reflection
     * @param object           $object
     *
     * @return array
     */
    private static function dumpMethods(\ReflectionClass $reflection, $object)
    {
        $methods = [];
        foreach ($reflection->getMethods() as $method) {

            $arguments = [];
            foreach ($method->getParameters() as $reflectionParameter) {

                $cast = $castType = null;
                if (null !== $class = $reflectionParameter->getClass()) {
                    $cast = $class->getShortName();
                    $castType = "object";
                } elseif ($reflectionParameter->isArray()) {
                    $cast = $castType = "array";
                }

                $defaultValue = $defaultValueType = null;
                if ($reflectionParameter->isDefaultValueAvailable()) {
                    $defaultValue = $reflectionParameter->getDefaultValue();
                    $defaultValueType = gettype($defaultValue);
                }

                $arguments[] = array(
                    "name"             => $reflectionParameter->getName(),
                    "cast"             => $cast,
                    "castType"         => $castType,
                    "defaultValue"     => $defaultValue,
                    "defaultValueType" => $defaultValueType,
                );
            }

            //DeclaringClass
            $declaringClass = $method->getDeclaringClass()->getName();
            if (get_class($object) === $declaringClass) {
                $declaringClass = null;
            }

            $methods[] = arraY(
                "name"           => $method->getName(),
                "access"         => self::dumpAccess($method),
                "arguments"      => $arguments,
                "declaringClass" => $declaringClass,
            );
        }

        return $methods;
    }

    /**
     * @param string $property
     *
     * @return string|null
     */
    private static function dumpAccess($property)
    {
        foreach (array("public", "protected", "private") as $access) {
            if (method_exists($property, $method = "is".ucfirst($access))) {
                if ($property->$method()) {
                    return $access;
                }
            }
        }

        return null;
    }

    /**
     * @param \ReflectionClass $reflection
     * @param object           $object
     *
     * @return array
     */
    private static function dumpProperties(\ReflectionClass $reflection, $object)
    {
        /** @var ReflectionProperty[] $properties */
        $properties = array_merge((array) $reflection->getProperties(), (array) $reflection->getStaticProperties());

        $end = array();

        foreach ($properties as $property) {

            if (!is_object($property)) {
                continue;
            }

            //Value
            $value = null;
            if (method_exists($property, "setAccessible")) {
                $property->setAccessible(true);
            }
            if (method_exists($property, "getValue")) {
                $value = $property->getValue($object);
                if (is_array($value)) {
                    $value = self::dumpArray($value);
                }
            }

            //DeclaringClass
            $declaringClass = null;
            if (method_exists($property, "getDeclaringClass")) {
                if (get_class($object) === $declaringClass = $property->getDeclaringClass()->getName()) {
                    $declaringClass = null;
                }
            }

            $end[] = array(
                "access"         => self::dumpAccess($property),
                "name"           => $property->getName(),
                "value"          => $value,
                "declaringClass" => $declaringClass,
            );
        }

        return $end;
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
                $value = $refl->getName();
                break;

            case "NULL":
                $value = "null";
                break;
        }

        return $value;
    }
}