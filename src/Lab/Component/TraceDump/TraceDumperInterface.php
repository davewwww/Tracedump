<?php

namespace Lab\Component\TraceDump;

/**
 *
 * @author David Wolter <david@dampfer.net>
 */
interface TraceDumperInterface {

    /**
     * @return string
     */
    public function tracedump();
}