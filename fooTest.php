<?php

class Foo implements ArrayAccess
{

    private $container = [];

    public function __construct(array $stuff)
    {
        $this->container = $stuff;
    }

    public function offsetExists($offset)
    {
    }

    public function offsetGet($offset)
    {
    }

    public function offsetSet($offset, $value)
    {
        throw new Exception('read only value');
    }

    public function offsetUnset($offset)
    {
        throw new Exception('cannot unset value');
    }

}

$foo = new Foo(['bar'=>'biz']);
$foo['bar'] = 'baz';
unset($foo['bar']);
