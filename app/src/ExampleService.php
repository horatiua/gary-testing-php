<?php // /src/ExampleService.php

namespace App;

class ExampleService
{
    private mixed $param1;
    private mixed $param2;

    public function __construct($param1, $param2)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
    }


    public function doSomething($arg)
    {
        return 'Random stuff..';
    }

    public function nonMockedMethod($arg)
    {
        return $this->doSomething($arg);
    }
}