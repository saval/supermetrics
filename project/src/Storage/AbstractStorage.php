<?php

namespace Storage;

abstract class AbstractStorage
{
    protected static $instance = null;
    
    abstract public static function getInstance(array $config): AbstractStorage;
    
    abstract protected function __clone();
    
    abstract public function saveData(string $type, array $data);
    
    abstract public function removeAll(string $type);
}
