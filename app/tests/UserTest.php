<?php

namespace App\Tests;

use App\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testExceptionsAreThrownForShortPasswords()
    {
        $user = new User();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The password must be at least 8 valid characters.');
        $this->expectExceptionMessage('at least 8 valid characters.');

        $user->setPassword('short');
    }

    public function testExceptionThrownIfPasswordContainsExcludedChars()
    {
        try {
            $user = new User();
            $user->setPassword('p@55w0rd');
            $this->fail('A InvalidArgumentException should have been thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString(
                '8 valid characters.',
                $e->getMessage()
            );
        }
    }
}