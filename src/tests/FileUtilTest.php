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
use Psr\Log\NullLogger;

class FileUtilTest extends TestCase
{

    const TEST_FILEPATH = '/tmp/file-util-test-dir/file-util-test-file';
    const TEST_CONTENT = 'foo bar baz';

    private $fileUtil;

    public function setUp()
    {
        $this->fileUtil = new FileUtil(new NullLogger());

        if (file_exists(self::TEST_FILEPATH)) {
            unlink(self::TEST_FILEPATH);
            rmdir(dirname(self::TEST_FILEPATH));
        }
    }

    public function testWriteFile_createDir()
    {
        $this->fileUtil->writeFile(self::TEST_FILEPATH, self::TEST_CONTENT);
        $this->assertTrue(is_dir(dirname(self::TEST_FILEPATH)));
    }

    /**
     * @expectedException Exception
     */
    public function testWriteFile_fileExists_exception()
    {
        $this->fileUtil->writeFile(self::TEST_FILEPATH, self::TEST_CONTENT);
        $this->fileUtil->writeFile(self::TEST_FILEPATH, self::TEST_CONTENT);
    }

    public function testWriteFile_fileCreated_fileExists()
    {
        $this->fileUtil->writeFile(self::TEST_FILEPATH, self::TEST_CONTENT);
        $this->assertTrue(file_exists(self::TEST_FILEPATH));
    }

    public function testWriteFile_fileCreated_hasContent()
    {
        $this->fileUtil->writeFile(self::TEST_FILEPATH, self::TEST_CONTENT);
        $this->assertEquals(self::TEST_CONTENT, file_get_contents(self::TEST_FILEPATH));
    }

}
