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

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class FileWriterTest extends TestCase
{
    const TEST_FILEPATH = '/tmp/file-util-test-dir/file-util-test-file';
    const TEST_CONTENT = 'foo bar baz';

    private $fileWriter;

    /**
     * @before
     */
    public function setUp()
    {
        $this->fileWriter = new FileWriter(new NullLogger());
    }

    /**
     * @after
     */
    public function cleanUp()
    {
        if (file_exists(self::TEST_FILEPATH)) {
            unlink(self::TEST_FILEPATH);
            rmdir(dirname(self::TEST_FILEPATH));
        }
    }

    /**
     * @test
     */
    public function writeFileCreateDir()
    {
        $this->fileWriter->write(self::TEST_FILEPATH, self::TEST_CONTENT);
        $this->assertTrue(is_dir(dirname(self::TEST_FILEPATH)));
    }

    /**
     * @test
     * @expectedException NoteScript\FileException
     * @expectedExceptionMessage NoteScript\FileWriter::MSG_FILE_EXISTS
     */
    public function writeFileFileExistsException()
    {
        $this->fileWriter->write(self::TEST_FILEPATH, self::TEST_CONTENT);
        $this->fileWriter->write(self::TEST_FILEPATH, self::TEST_CONTENT);
    }

    /**
     * @test
     */
    public function writeFileFileCreatedFileExists()
    {
        $this->fileWriter->write(self::TEST_FILEPATH, self::TEST_CONTENT);
        $this->assertTrue(file_exists(self::TEST_FILEPATH));
    }

    /**
     * @test
     */
    public function writeFileFileCreatedHasContent()
    {
        $this->fileWriter->write(self::TEST_FILEPATH, self::TEST_CONTENT);
        $this->assertEquals(self::TEST_CONTENT, file_get_contents(self::TEST_FILEPATH));
    }

    /**
     * @test
     */
    public function writeReturnsGivenPath()
    {
        $this->assertEquals(self::TEST_FILEPATH, $this->fileWriter->write(self::TEST_FILEPATH, self::TEST_CONTENT));
    }
}
