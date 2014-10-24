<?php

/**
 * Registry class
 */
class Registry
{

    private $data = [];

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function remove($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

}
