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

class ConfigTest extends TestCase
{

    const TEST_KEY = 'foo';
    const TEST_VALUE = 'bar';
    const TEST_HOME = '/tmp/home';
    const TEST_NOTE_HOME = '/tmp/note_home';

    public function testOffsetExists_asFunction_true()
    {
        $config = $this->getTestObjectPopulated();
        $this->assertTrue($config->offsetExists(self::TEST_KEY));
    }

    public function testOffsetExists_asFunction_false()
    {
        $config = $this->getTestObjectPopulated();
        $this->assertTrue(isset($config[self::TEST_KEY]));
    }

    public function testOffsetExists_asArray_true()
    {
        $config = $this->getTestObjectEmpty();
        $this->assertFalse($config->offsetExists(self::TEST_KEY));
    }

    public function testOffsetExists_asArray_false()
    {
        $config = $this->getTestObjectEmpty();
        $this->assertFalse(isset($config[self::TEST_KEY]));
    }

    public function testOffsetGet_asFunction_exists()
    {
        $config = $this->getTestObjectPopulated();
        $this->assertEquals(self::TEST_VALUE, $config->offsetGet(self::TEST_KEY));
    }

    public function testOffsetGet_asFunction_notExists()
    {
        $config = $this->getTestObjectEmpty();
        $this->assertNull($config->offsetGet(self::TEST_KEY));
    }

    public function testOffsetGet_asArray_exists()
    {
        $config = $this->getTestObjectPopulated();
        $this->assertEquals(self::TEST_VALUE, $config[self::TEST_KEY]);
    }

    public function testOffsetGet_asArray_notExists()
    {
        $config = $this->getTestObjectEmpty();
        $this->assertNull($config[self::TEST_KEY]);
    }

    /**
     * @expectedException NoteScript\ConfigException
     * @expectedExceptionMessage NoteScript\ConfigException::MSG_READ_ONLY
     */
    public function testOffsetSet_asFunction_withException()
    {
        $config = $this->getTestObjectEmpty();
        $config->offsetSet(self::TEST_KEY, self::TEST_VALUE);
    }

    /**
     * @expectedException NoteScript\ConfigException
     * @expectedExceptionMessage NoteScript\ConfigException::MSG_READ_ONLY
     */
    public function testOffsetSet_asArray_withException()
    {
        $config = $this->getTestObjectEmpty();
        $config[self::TEST_KEY] = self::TEST_VALUE;
    }

    /**
     * @expectedException NoteScript\ConfigException
     * @expectedExceptionMessage NoteScript\ConfigException::MSG_NOT_REMOVABLE
     */
    public function testOffsetUnset_asFunction_withException()
    {
        $config = $this->getTestObjectPopulated();
        $config->offsetUnset(self::TEST_KEY);
    }

    /**
     * @expectedException NoteScript\ConfigException
     * @expectedExceptionMessage NoteScript\ConfigException::MSG_NOT_REMOVABLE
     */
    public function testOffsetUnset_asArray_withException()
    {
        $config = $this->getTestObjectPopulated();
        unset($config[self::TEST_KEY]);
    }

    /**
     * @expectedException Exception
     */
    public function testFoo()
    {
        include dirname(dirname(__DIR__)) . '/fooTest.php';
    }

    public function testCreate_noteHome()
    {
        putenv(Config::ENV_VAR_NOTE_HOME . '=' . self::TEST_NOTE_HOME);
        $config = Config::create();
        $this->assertEquals(self::TEST_NOTE_HOME, $config[Config::NOTE_DIR]);
    }

    public function testCreate_noNoteHome()
    {
        putenv(Config::ENV_VAR_HOME . '=' . self::TEST_HOME);
        putenv(Config::ENV_VAR_NOTE_HOME);
        $config = Config::create();
        $this->assertEquals(self::TEST_HOME . Config::DEFAULT_NOTE_DIR, $config[Config::NOTE_DIR]);
    }

    /**
     * @expectedException NoteScript\ConfigException
     */
    public function testCreate_noHome_exception()
    {
        putenv(Config::ENV_VAR_HOME);
        putenv(Config::ENV_VAR_NOTE_HOME);
        $config = Config::create();
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
