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
use NoteScript\Terminal\Response;
use Psr\Log\LoggerInterface;

/**
 * The primary entry point for the application. All application execution is funneled through this
 * class.
 */
class Main
{
    /**
     * @var NoteScript\NoteWriter
     */
    private $noteWriter;

    /**
     * @var Psr\Log\LoggerInterface
     */
    private $log;

    /**
     * Constructor
     * @param NoteScript\NoteWriter $noteWriter
     * @param Psr\Log\LoggerInterface $logger
     */
    private function __construct(
        NoteWriter $noteWriter,
        LoggerInterface $logger
    ) {
        $this->noteWriter = $noteWriter;
        $this->log = $logger;
    }

    /**
     * @return NoteScript\Terminal\Response
     */
    public static function run()
    {
        try {
            $logger = new Logger();
            $fileWriter = new FileWriter($logger);
            $noteWriter = new NoteWriter($fileWriter, new Note\NoteFormatter(), new StringSimplifier());
            return new Response(0, (new Main($noteWriter, $logger))->process());
        } catch (Exception $exception) {
            return new Response(1, null, (new ErrorHandler())->printException($exception));
        }
    }

    public function process()
    {
        return $this->noteWriter->writeNote(
            $this->getNoteDirectory(),
            new Note\Note($this->getTitleFromArgs(), $this->readStdIn())
        );
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
     * Retrieve any standard in content that was piped into the script.
     * @return string
     */
    private function readStdIn()
    {
        // prevent the read from waiting for user input
        stream_set_blocking(STDIN, 0);
        return trim(stream_get_contents(STDIN)) . PHP_EOL;
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
