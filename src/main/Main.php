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
     * @var NoteScript\Config
     */
    private $config;

    /**
     * @var NoteScript\FileUtil
     */
    private $fileUtil;

    /**
     * @var Psr\Log\LoggerInterface
     */
    private $log;

    /**
     * Constructor
     * @param NoteScript\Config       $config
     * @param Psr\Log\LoggerInterface $logger
     */
    private function __construct(Config $config, FileUtil $fileUtil, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->fileUtil = $fileUtil;
        $this->log = $logger;
    }

    public static function run()
    {
        try {
            $config = Config::create();
            $logger = new Logger();
            $fileUtil = new FileUtil($logger);
            echo (new Main($config, $fileUtil, $logger))->process();
            exit(0);
        } catch (Exception $e) {
            ErrorHandler::printException($e);
            exit(1);
        }
    }

    public function process()
    {
        $date = new DateTime();
        $title = $this->getTitleFromArgs();
        $header = self::generateNoteHeader($date, $title);
        $fileName = self::generateFileName($date, $title);
        $filePath = sprintf('%s/%s.md', $this->config[Config::NOTE_DIR], $fileName);
        $this->fileUtil->writeFile($filePath, $header);

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
        $args = array_slice($_SERVER['argv'], 1);
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
    public static function generateFileName(DateTime $date, $title = null)
    {
        $fileName = str_replace(' ', '-', $date->format(self::DATE_FORMAT));

        if ($title) {
            $fileName .= '_' . StringUtil::simplify($title);
        }

        return $fileName;
    }

    /**
     * Generates header text for a new note file.
     * @param  DateTime    $date  A date to be used in the header
     * @param  string|null $title An optional title to use in generating the header.
     * @return string
     */
    public static function generateNoteHeader(DateTime $date, $title = null)
    {
        $header = sprintf("# Note %s\n\n", $date->format(self::DATE_FORMAT));

        if ($title) {
            $header = sprintf("# %s\n%s\n\n", $title, $date->format(self::DATE_FORMAT));
        }

        return $header;
    }


}
