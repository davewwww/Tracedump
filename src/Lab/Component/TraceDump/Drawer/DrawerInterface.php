<?php

namespace Lab\Component\TraceDump\Drawer;

/**
 * @author David Wolter <david@dampfer.net>
 */
interface DrawerInterface
{
    /**
     * @param array $data
     * @param int   $deep
     * @param int   $pos
     *
     * @return array
     */
    public function draw(array $data, $deep = 0, $pos = 0);
}
