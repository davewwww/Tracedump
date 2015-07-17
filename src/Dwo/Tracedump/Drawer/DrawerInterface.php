<?php

namespace Dwo\Tracedump\Drawer;

/**
 * @author Dave Www <davewwwo@gmail.com>
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
