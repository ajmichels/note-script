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

use Exception;
use Psr\Log\LoggerInterface;

/**
 * Provides methods for manipulating files.
 */
class FileUtil
{

    const DEFAULT_DIR_MODE = 0700;
    const DEFAULT_FILE_MODE = 0600;
    const MSG_FILE_EXISTS = 'File already exists (%s)';
    const MSG_FILE_CREATED = 'File Created (%s)';

    /**
     * @var Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Will write contents to provided file path. If the directory for the file doesn't exist it
     * will be created. If the file already exists an exception will be thrown.
     * @param  string $path    The file path to write to.
     * @param  string $content The content to write to the file.
     * @throws Exception
     * @return null
     */
    public function writeFile($path, $content)
    {
        $directory = dirname($path);

        // Create the directory if it doesn't exist
        if (!is_dir($directory)) {
            mkdir($directory, self::DEFAULT_DIR_MODE, true);
        }

        if (file_exists($path)) {
            $msg = sprintf(self::MSG_FILE_EXISTS, $path);
            $this->logger->warning($msg);
            throw new Exception($msg);
        }

        $file = fopen($path, 'x');
        fwrite($file, $content);
        fclose($file);
        chmod($path, self::DEFAULT_FILE_MODE);
        $this->logger->info(sprintf(self::MSG_FILE_CREATED, $path));
    }

}
