<?php

/**
 * Copyright 2015, AJ Michels
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

use DateTime;
use Exception;
use ErrorException;
use Psr\Log\LoggerInterface;

/**
 * The primary entry point for the application. All application execution is funneled through this
 * class.
 */
class Main
{
    const DATE_FORMAT = 'Y-m-d h-i-s';

    /**
     * @var NoteScript\FileUtil
     */
    private $fileUtil;

    /**
     * @var NoteScript\StringUtil
     */
    private $stringUtil;

    /**
     * @var Psr\Log\LoggerInterface
     */
    private $log;

    /**
     * Constructor
     * @param NoteScript\FileUtil $fileUtil
     * @param NoteScript\StringUtil $stringUtil
     * @param Psr\Log\LoggerInterface $logger
     */
    private function __construct(
        FileUtil $fileUtil,
        StringUtil $stringUtil,
        LoggerInterface $logger
    ) {
        $this->fileUtil = $fileUtil;
        $this->stringUtil = $stringUtil;
        $this->log = $logger;
    }

    public static function run()
    {
        try {
            $logger = new Logger();
            $fileUtil = new FileUtil($logger);
            echo (new Main($fileUtil, new StringUtil(), $logger))->process();
            return 0;
        } catch (Exception $e) {
            (new ErrorHandler())->printException($e);
            return 1;
        }
    }

    public function process()
    {
        $date = new DateTime();
        $title = $this->getTitleFromArgs();
        $header = $this->generateNoteHeader($date, $title);
        $fileName = $this->generateFileName($date, $title);
        $filePath = sprintf('%s/%s.md', $this->getNoteDirectory(), $fileName);
        $this->fileUtil->writeFile($filePath, $header . $this->readStdIn());

        return $filePath;
    }

    /**
     * Retrieves a title value from command line arguments.
     * @throws Exception
     * @return string|null
     */
    private function getTitleFromArgs()
    {
        $title = null;
        $args = $this->getTerminalArguments();
        $titleArg = array_search('-t', $args);
        $titleArg = $titleArg === false ? array_search('--title', $args) : $titleArg;

        if ($titleArg !== false) {
            try {
                $title = trim($args[$titleArg+1]) ?: null;

            } catch (ErrorException $exception) {
                if (preg_match('/Undefined offset/i', $exception->getMessage())) {
                    $this->log->warning('Missing Title Value');
                    throw new Exception('Argument `' . $args[$titleArg] . '` must be followed by a value.');
                }

                throw $exception;
            }

            if (substr($title, 0, 1) === '-' || substr($title, 0, 2) === '--') {
                $this->log->warning('Invalid Note Title', [$title]);
                throw new Exception('Title cannot start with - or --');
            }
        }

        return $title;
    }

    /**
     * Generate a file name.
     * @param  DateTime $date  A date to be used in the file name
     * @param  string|null $title A title to include in the file name
     * @return string
     */
    public function generateFileName(DateTime $date, $title = null)
    {
        $fileName = str_replace(' ', '-', $date->format(self::DATE_FORMAT));

        if ($title) {
            $fileName .= '_' . $this->stringUtil->simplify($title);
        }

        return $fileName;
    }

    /**
     * Generates header text for a new note file.
     * @param  DateTime    $date  A date to be used in the header
     * @param  string|null $title An optional title to use in generating the header.
     * @return string
     */
    public function generateNoteHeader(DateTime $date, $title = null)
    {
        $header = sprintf("# Note %s\n\n", $date->format(self::DATE_FORMAT));

        if ($title) {
            $header = sprintf("# %s\n%s\n\n", $title, $date->format(self::DATE_FORMAT));
        }

        return $header;
    }

    /**
     * Retrieve any standard in content that was piped into the script.
     * @return string
     */
    public function readStdIn()
    {
        // prevent the read from waiting for user input
        stream_set_blocking(STDIN, 0);
        return trim(stream_get_contents(STDIN));
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function getTerminalArguments()
    {
        return array_slice($_SERVER['argv'], 1);
    }

    /**
     * Retrieves the directory for storing notes by reading certain environment variables.
     * @throws NoteScript\ConfigException
     * @return string Path to directory
     */
    private static function getNoteDirectory()
    {
        $noteDir = getenv('NOTE_HOME');

        if ($noteDir === false || trim($noteDir) === '') {
            $homeDir = getenv('HOME');

            if ($homeDir === false || trim($homeDir) === '') {
                throw new ConfigException(sprintf(ConfigException::MSG_MISSING_ENV_VAR, 'HOME'));
            }

            $noteDir = $homeDir . '/notes';
        }

        return $noteDir;
    }
}
