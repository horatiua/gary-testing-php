<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class TestWarnings extends TestCase
{
    /** @test */
    public function warnings()
    {
        // Setup
        $arr = ['a' => 'a', 'b' => 'b', 'c' => 'c'];

        // Do
        dump(error_reporting());
        // dump($arr['z']);
        // dd($arr);
        // Assert
        $this->assertTrue(true);
    }
}