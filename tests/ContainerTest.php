<?php

namespace NoteScript;

use PHPUnit_Framework_TestCase as TestCase;

class ContainerTest extends TestCase
{
    const TEST_NAME = 'test';

    /**
     * @test
     */
    public function registerFactory()
    {
        $container = new Container();
        $container->registerFactory(self::TEST_NAME, function () {
        });
    }

    /**
     * @test
     * @expectedException NoteScript\ContainerException
     * @expectedExceptionMessage Factory not found
     */
    public function getThrowsExceptionOnFactoryNotFound()
    {
        $container = new Container();
        $container->get(self::TEST_NAME);
    }

    /**
     * @test
     */
    public function getReturnsObject()
    {
        $originalObject = new \stdClass();
        $container = new Container();
        $container->registerFactory(self::TEST_NAME, function () use ($originalObject) {
            return $originalObject;
        });
        $this->assertSame($originalObject, $container->get(self::TEST_NAME));
    }

    /**
     * @test
     */
    public function factoriesHaveAccessToContainer()
    {
        $container = new Container();
        $container->registerFactory(self::TEST_NAME, function ($container) {
            return $container;
        });
        $this->assertSame($container, $container->get(self::TEST_NAME));
    }

    /**
     * @test
     */
    public function getReturnsSingleton()
    {
        $container = new Container();
        $container->registerFactory(self::TEST_NAME, function () {
            return new \stdClass();
        });
        $this->assertSame(
            $container->get(self::TEST_NAME),
            $container->get(self::TEST_NAME)
        );
    }

    /**
     * @test
     */
    public function getCallsConfig()
    {
        $config = ['bar' => uniqid()];
        $container = new Container($config);
        $container->registerFactory(self::TEST_NAME, function ($container) {
            $obj = new \stdClass();
            $obj->foo = $container->getConfig()['bar'];
            return $obj;
        });
        $this->assertEquals($config['bar'], $container->get(self::TEST_NAME)->foo);
    }
}
