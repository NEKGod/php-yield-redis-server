<?php

namespace core\struct;

use core\library\Single;

/**
 *
 */
class KeyStruct
{
    use Single;

    private array $data = [];

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key){
        return $this->data[$key] ?? null;
    }

    /**
     * @param $key
     * @return bool
     */
    public function del($key): bool
    {
        unset($this->data[$key]);
        return true;
    }

    public function exists($key): bool
    {
        return isset($this->data[$key]);
    }
}