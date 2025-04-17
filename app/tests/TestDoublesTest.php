<?php

namespace App\Tests;

use App\ExampleCommand;
use App\ExampleDependency;
use App\ExampleService;
use PHPUnit\Framework\TestCase;

class TestDoublesTest extends TestCase
{
    public function testMock()
    {
        // Setup
        $mock = $this->createMock(ExampleService::class);
        $mock
            ->expects($this->exactly(1))
            ->method('doSomething')
            ->with('bar')
            ->willReturn('foo');

        $exampleCommand = new ExampleCommand($mock);

        // Do


        // Assert
        $this->assertSame('foo', $exampleCommand->execute('bar'));
    }

    public function testReturnType()
    {
        // Setup
        $mock = $this->createMock(ExampleService::class);

        // Do

        // Assert
        $this->assertNull($mock->doSomething('bar'));
    }

    public function testConsecutiveReturns()
    {
        // Setup
        $mock = $this->createMock(ExampleService::class);
        $mock
            ->method('doSomething')
            ->will($this->onConsecutiveCalls(1, 2));

        // Do

        // Assert
        foreach([1, 2] as $value) {
            $this->assertSame($value, $mock->doSomething('bar'));
        }
    }

    public function testExceptionsThrown()
    {
        // Setup
        $mock = $this->createMock(ExampleService::class);
        $mock
            ->method('doSomething')
            ->willThrowException(new \RuntimeException());


        // Assert
        $this->expectException(\RuntimeException::class);

        // Do
        $mock->doSomething('bar');
    }

    public function testCallbackReturns()
    {
        // Setup
        $mock = $this->createMock(ExampleService::class);
        $mock
            ->method('doSomething')
            ->willReturnCallback(function ($arg) {
                if ($arg % 2 == 0) {
                    return $arg;
                }

                throw new \InvalidArgumentException();
            });

        // Assert
        $this->assertSame(10, $mock->doSomething(10));
        $this->expectException(\InvalidArgumentException::class);

        // Do
        $mock->doSomething(9);
    }

    public function testWithEqualTo()
    {
        // Setup
        $mock = $this->createMock(ExampleService::class);
        $mock
            ->expects($this->once())
            ->method('doSomething')
            ->with($this->equalTo('bar'));

        // Do
        $mock->doSomething('bar');

        // Assert

    }

    public function testMultipleArgs()
    {
        // Setup
        $mock = $this->createMock(ExampleService::class);
        $mock
            ->expects($this->once())
            ->method('doSomething')
            ->with(
                $this->stringContains('foo'),
                $this->greaterThanOrEqual(100),
                $this->anything()
            );

        // Do
        $mock->doSomething('foo', 100, null);

        // Assert

    }

    public function testCallbackArgs()
    {
        // Setup
        $mock = $this->createMock(ExampleService::class);
        $mock
            ->expects($this->once())
            ->method('doSomething')
            ->with($this->callback(function ($object) {
                $this->assertInstanceOf(ExampleDependency::class, $object);

                return true;
            }));

        // Do
        $mock->doSomething(new ExampleDependency());

        // Assert

    }

    public function testIdenticalTo()
    {
        // Setup
        $exampleDependency = new ExampleDependency();
        $mock = $this->createMock(ExampleService::class);
        $mock
            ->expects($this->once())
            ->method('doSomething')
            ->with($this->identicalTo($exampleDependency));

        // Do
        $mock->doSomething($exampleDependency);

        // Assert

    }

    public function testMockBuilder()
    {
        // Setup
        $mock = $this->getMockBuilder(\App\ExampleService::class)
            ->setConstructorArgs([100, 200])
            ->getMock();

        $mock->method('doSomething')->willReturn('foo');

        // Assert
        $this->assertSame('foo', $mock->doSomething('bar'));
    }

    public function testOnlyMethods()
    {
        // Setup
        $mock = $this->getMockBuilder(ExampleService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['doSomething'])
            ->getMock();

        $mock->method('doSomething')->willReturn('foo');

        // Assert
        $this->assertSame('foo', $mock->nonMockedMethod('bar'));
    }

    public function testAddMethods()
    {
        // Setup
        $mock = $this->getMockBuilder(ExampleService::class)
            ->disableOriginalConstructor()
            ->addMethods(['nonExistentMethod'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('nonExistentMethod')
            ->with($this->isInstanceOf(ExampleDependency::class))
            ->willReturn('foo');

        // Assert
        $this->assertSame('foo', $mock->nonExistentMethod(new ExampleDependency()));
    }
}