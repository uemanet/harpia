<?php

namespace Harpia\Menu;

use Harpia\Tree\Tree;

class MenuTree extends Tree
{
    public function __construct()
    {
        $this->nodes = 0;
        $this->root = null;
    }
}
