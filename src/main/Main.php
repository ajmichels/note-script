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

use \Exception;
use \ErrorException;

class Main
{


    private static $instance;
    private $log;


    private function __construct()
    {
        $this->log = new Logger(dirname(dirname(__DIR__)) . '/logs');
    }


    public static function run()
    {
        self::$instance = self::$instance ?: new self();

        try {
            self::$instance->process();
        } catch (Exception $e) {
            self::printException($e);
            exit(1);
        }

        return self::$instance;

    }


    public function process()
    {

        $noteDir = getenv('NOTE_HOME');

        // Establish the directory where notes will be stored
        if ($noteDir === false) {
            $homeDir = getenv('HOME');

            if ($homeDir !== false) {
                $noteDir = $homeDir . '/notes';
            } else {
                $this->log->error('Configuration Error, No $HOME env var');
                throw new Exception('There is no $HOME environment variable defined.');
            }

        }

        // Capture arguments
        $args = array_slice($_SERVER['argv'], 1);

        $t = array_search('-t', $args);
        $t = $t === false ? array_search('--title', $args) : $t;

        if ($t !== false) {
            try {
                $title = trim($args[$t+1]) ?: null;

            } catch (ErrorException $e) {
                if (preg_match('/Undefined offset/i', $e->getMessage())) {
                    $this->log->warning('Missing Title Value');
                    throw new Exception('Argument `' . $args[$t] . '` must be followed by a value.');
                }

            }

            if (substr($title, 0, 1) === '-' || substr($title, 0, 2) === '--') {
                $this->log->warning('Invalid Note Title', [$title]);
                throw new Exception('Title cannot start with - or --');
            }

        } else {
            $title = null;

        }

        // Create the directory if it doesn't exist
        if (!is_dir($noteDir)) {
            mkdir($noteDir);
        }

        // Define the filename
        $currentDate = date('Y-m-d h-i-s');
        $fileName = str_replace(' ', '-', $currentDate);

        if ($title) {
            $fileName .= '_' . $this->simplifyString($title);
            $heading = "# ${title}\n${currentDate}\n\n";
        } else {
            $heading = "# Note ${currentDate}\n\n";
            $this->log->info('Note Title Not Specified, Using Default', [$heading]);
        }

        $content = $heading;

        $filePath = $noteDir . '/' . $fileName . '.md';

        // Create it if it doesn't exist
        if (!file_exists($filePath)) {
            $this->log->info('Note Created', [$filePath]);
            file_put_contents($filePath, $content);
        } else {
            $this->log->warning('Note Already Exists', [$filePath]);
            throw new Exception('There is already a note with this title (' . $filePath . ').');
        }

        // Output the path
        echo $filePath;

    }


    private function simplifyString($str)
    {
        $str = strtolower($str);
        $str = preg_replace('/[^a-zA-Z\d\s\\\\\-:]/', '', $str);
        $str = preg_replace('/[\s\\\\]+/', '-', $str);
        $str = substr($str, 0, 235);
        $str = trim($str, '-');

        return $str;

    }


    public static function printException(Exception $e)
    {
        $stderr = fopen('php://stderr', 'w');
        $argv = $_SERVER['argv'];

        $verbose = array_search('-v', $argv);
        $verbose = $verbose === false ? array_search('--verbose', $argv) : $verbose;

        if ($verbose !== false) {
            fwrite($stderr, (string) $e);
        } else {
            fwrite($stderr, $e->getMessage());
        }

    }


}
