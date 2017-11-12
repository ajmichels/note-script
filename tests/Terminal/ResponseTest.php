<?php

namespace NoteScript\Terminal;

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidReturnCodes
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $returnCode must be an integer
     */
    public function invalidReturnCodeThrowsException($value)
    {
        new Response($value);
    }

    public function invalidReturnCodes()
    {
        return [['foo'],[false],[true],[new \stdClass()],[1.234]];
    }

    /**
     * @test
     * @dataProvider invalidStringData
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $output must be a string
     */
    public function invalidOutputThrowsException($value)
    {
        new Response(1, $value);
    }

    /**
     * @test
     * @dataProvider invalidStringData
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $error must be a string
     */
    public function invalidErrorThrowsException($value)
    {
        new Response(1, null, $value);
    }

    public function invalidStringData()
    {
        return [[123],[false],[true],[new \stdClass()],[1.234]];
    }

    /**
     * @test
     * @dataProvider validArguments
     */
    public function constructorCreatesInstanceOfResponse($returnCode, $output, $error)
    {
        $this->assertInstanceOf(Response::class, new Response($returnCode, $output, $error));
    }

    public function validArguments()
    {
        return [
            [0, null, null],
            [0, 'foo', null],
            [1, null, 'bar'],
            [1, 'foo', 'bar'],
        ];
    }
}
