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

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class ConfigTest extends TestCase
{
    const TEST_KEY = 'foo';
    const TEST_VALUE = 'bar';
    const TEST_HOME = '/tmp/home';
    const TEST_NOTE_HOME = '/tmp/note_home';

    /**
     * @test
     */
    public function offsetExistsAsFunctionTrue()
    {
        $config = $this->getTestObjectPopulated();
        $this->assertTrue($config->offsetExists(self::TEST_KEY));
    }

    /**
     * @test
     */
    public function offsetExistsAsFunctionFalse()
    {
        $config = $this->getTestObjectPopulated();
        $this->assertTrue(isset($config[self::TEST_KEY]));
    }

    /**
     * @test
     */
    public function offsetExistsAsArrayTrue()
    {
        $config = $this->getTestObjectEmpty();
        $this->assertFalse($config->offsetExists(self::TEST_KEY));
    }

    /**
     * @test
     */
    public function offsetExistsAsArrayFalse()
    {
        $config = $this->getTestObjectEmpty();
        $this->assertFalse(isset($config[self::TEST_KEY]));
    }

    /**
     * @test
     */
    public function offsetGetAsFunctionExists()
    {
        $config = $this->getTestObjectPopulated();
        $this->assertEquals(self::TEST_VALUE, $config->offsetGet(self::TEST_KEY));
    }

    /**
     * @test
     */
    public function offsetGetAsFunctionNotExists()
    {
        $config = $this->getTestObjectEmpty();
        $this->assertNull($config->offsetGet(self::TEST_KEY));
    }

    /**
     * @test
     */
    public function offsetGetAsArrayExists()
    {
        $config = $this->getTestObjectPopulated();
        $this->assertEquals(self::TEST_VALUE, $config[self::TEST_KEY]);
    }

    /**
     * @test
     */
    public function offsetGetAsArrayNotExists()
    {
        $config = $this->getTestObjectEmpty();
        $this->assertNull($config[self::TEST_KEY]);
    }

    /**
     * @test
     * @expectedException NoteScript\ConfigException
     * @expectedExceptionMessage NoteScript\ConfigException::MSG_READ_ONLY
     */
    public function offsetSetAsFunctionWithException()
    {
        $config = $this->getTestObjectEmpty();
        $config->offsetSet(self::TEST_KEY, self::TEST_VALUE);
    }

    /**
     * @test
     * @expectedException NoteScript\ConfigException
     * @expectedExceptionMessage NoteScript\ConfigException::MSG_READ_ONLY
     */
    public function offsetSetAsArrayWithException()
    {
        $config = $this->getTestObjectEmpty();
        $config[self::TEST_KEY] = self::TEST_VALUE;
    }

    /**
     * @test
     * @expectedException NoteScript\ConfigException
     * @expectedExceptionMessage NoteScript\ConfigException::MSG_NOT_REMOVABLE
     */
    public function offsetUnsetAsFunctionWithException()
    {
        $config = $this->getTestObjectPopulated();
        $config->offsetUnset(self::TEST_KEY);
    }

    /**
     * @test
     * @expectedException NoteScript\ConfigException
     * @expectedExceptionMessage NoteScript\ConfigException::MSG_NOT_REMOVABLE
     */
    public function offsetUnsetAsArrayWithException()
    {
        $config = $this->getTestObjectPopulated();
        unset($config[self::TEST_KEY]);
    }

    /**
     * @test
     */
    public function createNoteHome()
    {
        putenv(Config::ENV_VAR_NOTE_HOME . '=' . self::TEST_NOTE_HOME);
        $config = Config::create();
        $this->assertEquals(self::TEST_NOTE_HOME, $config[Config::NOTE_DIR]);
    }

    /**
     * @test
     */
    public function createNoNoteHome()
    {
        putenv(Config::ENV_VAR_HOME . '=' . self::TEST_HOME);
        putenv(Config::ENV_VAR_NOTE_HOME);
        $config = Config::create();
        $this->assertEquals(self::TEST_HOME . Config::DEFAULT_NOTE_DIR, $config[Config::NOTE_DIR]);
    }

    /**
     * @test
     * @expectedException NoteScript\ConfigException
     */
    public function createNoHomeException()
    {
        putenv(Config::ENV_VAR_HOME);
        putenv(Config::ENV_VAR_NOTE_HOME);
        Config::create();
    }

    private function getTestObjectPopulated()
    {
        return new Config([self::TEST_KEY => self::TEST_VALUE]);
    }

    private function getTestObjectEmpty()
    {
        return new Config([]);
    }
}
