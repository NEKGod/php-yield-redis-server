<?php

namespace core\command;

abstract class CommandBase implements CommandInterface
{
    private array $commandOptions = [
        'args' => []
    ];

    public function input($name, $index, $required = false)
    {
        $this->commandOptions['args'][$index] = [
            'name' => $name,
            'required' => $required
        ];
    }

    public function baseExecute(array $args)
    {
        $resArgs = [];
        foreach ($this->commandOptions['args'] as $index => $item) {
            if (isset($args[$index])) {
                $resArgs[$item['name']] = $args[$index];
            }
        }
        return $this->execute($resArgs);
    }
}