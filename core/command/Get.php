<?php

namespace core\command;

use core\struct\KeyStruct;

class Get extends CommandBase
{
    public function __construct()
    {
        $this->input('key', 0);
    }

    public function execute(array $args = [])
    {
        return KeyStruct::getSingle()->get($args['key']);
    }
}