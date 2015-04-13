<?php

namespace Lab\Component\TraceDump\Styler\Schema;

/**
 * @author David Wolter <david@dampfer.net>
 */
interface SchemaInterface
{
    const NONE = 'none';

    const VARIABLE = 'variable';
    const STRING = 'string';
    const INTEGER = 'integer';
    const BOOLEAN_TRUE = 'boolean_true';
    const BOOLEAN_FALSE = 'boolean_false';
    const OBJECT = 'object';
    const OBJECT_SUB = 'object_sub';
    const KEYWORD_FUNCTION = 'keyword_function';
    const METHOD = 'method';

    /**
     * @return array
     */
    public static function getAll();

}
