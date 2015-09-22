<?php

namespace Ajmichels\NoteScript;

use \Exception;
use \ErrorException;

class Main
{


    private static $instance;


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
                    throw new Exception('Argument `' . $args[$t] . '` must be followed by a value.');
                }

            }

            if (substr($title, 0, 1) === '-' || substr($title, 0, 2) === '--') {
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
            $content = "# ${title}\n${currentDate}\n\n";
        } else {
            $content = "# Note ${currentDate}\n\n";
        }

        $filePath = $noteDir . '/' . $fileName . '.md';

        // Create it if it doesn't exist
        if (!file_exists($filePath)) {
            file_put_contents($filePath, $content);
        } else {
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


    public static function handleError($severity, $message, $filename, $lineno)
    {

        if (error_reporting() == 0) {
            return;
        }

        if (error_reporting() & $severity) {
            throw new ErrorException($message, 0, $severity, $filename, $lineno);
        }

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
