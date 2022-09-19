<?php

namespace core\handle;

use core\library\Single;

class SetHandle
{
    use Single;

    private array $data = [];

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key){
        return $this->data[$key] ?? null;
    }
}