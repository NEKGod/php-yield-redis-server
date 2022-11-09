<?php

namespace core\command;

use core\struct\KeyStruct;

class Del extends CommandBase
{

    public function __construct()
    {
        $this->input('key', 0);
    }

    public function execute(array $args = []): bool
    {

        return KeyStruct::getSingle()->del($args['key']);
    }
}