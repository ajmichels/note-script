<?php

/**
 * Copyright 2016, AJ Michels
 *
 * This file is part of Note-Script.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if
 * not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace NoteScript;

use PHPUnit_Framework_TestCase as TestCase;

class ErrorHandlerTest extends TestCase
{
    const TEST_SEVERITY = E_ERROR;
    const TEST_MESSAGE = 'test message';
    const TEST_FILENAME = '/tmp/foo.php';
    const TEST_LINE = 123;

    /**
     * @test
     */
    public function handleNoErrorReportingNull()
    {
        ini_set('error_reporting', 0);
        $errorHandler = new ErrorHandler();
        $errorHandler->handle(
            self::TEST_SEVERITY,
            self::TEST_MESSAGE,
            self::TEST_FILENAME,
            self::TEST_LINE
        );
        $this->assertTrue(true); // No exception has occurred
    }

    /**
     * @test
     * @expectedException ErrorException
     * @expectedExceptionMessage NoteScript\ErrorHandlerTest::TEST_MESSAGE
     */
    public function handleErrorException()
    {
        ini_set('error_reporting', E_ALL);
        $errorHandler = new ErrorHandler();
        $errorHandler->handle(
            self::TEST_SEVERITY,
            self::TEST_MESSAGE,
            self::TEST_FILENAME,
            self::TEST_LINE
        );
    }
}
