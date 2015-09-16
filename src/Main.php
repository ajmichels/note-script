<?php

namespace Ajmichels\NoteScript;

use \Exception;

class Main
{


    public static function run()
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

        // Create the directory if it doesn't exist
        if (!is_dir($noteDir)) {
            mkdir($noteDir);
        }

        // Define the filename
        $currentDate = date('Y-m-d h-i-s');
        $fileName = str_replace(' ', '-', $currentDate) . '.md';
        $filePath = $noteDir . '/' . $fileName;

        // Create it if it doesn't exist
        if (!file_exists($filePath)) {
            file_put_contents($filePath, "# Note ${currentDate}\n\n");
        }

        // Output the path
        echo $filePath;

    }


}
