<?php

namespace core\command;

use core\struct\KeyStruct;

class Exists extends CommandBase
{
    public function __construct()
    {
        $this->input('key', 0);
    }

    public function execute(array $args = []): bool
    {
        return KeyStruct::getSingle()->exists($args['key']);
    }
}