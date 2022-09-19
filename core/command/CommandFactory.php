<?php

namespace core\command;

use Exception;

class CommandFactory
{
    public static array $classMap = ['set' => Set::class, 'get' => Get::class,];

    const COMMAND = 0;

    public static function execCommand($command)
    {
        $command = explode(' ', $command);
        if (empty(static::$classMap[$command[static::COMMAND]])) {
            throw new Exception("命令不存在");
        }
        return call_user_func([new static::$classMap[$command[static::COMMAND]], 'baseExecute'], array_slice($command, 1));
    }

}