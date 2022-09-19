<?php

namespace core\command;

use core\handle\SetHandle;

class Get extends CommandBase
{
    public function __construct()
    {
        $this->input('key', 0);
    }

    public function execute(array $args = [])
    {
        return SetHandle::getSingle()->get($args['key']);
    }
}