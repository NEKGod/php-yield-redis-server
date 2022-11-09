<?php

namespace core\command;

use core\struct\KeyStruct;

class Set extends CommandBase
{

    public function __construct(){
        $this->input('key', 0);
        $this->input('value', 1);
    }

    public function execute(array $args = []): bool
    {
        KeyStruct::getSingle()->set($args['key'], $args['value']);
        return true;
    }
}