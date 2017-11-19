<?php

namespace NoteScript\Note;

use NoteScript\FileWriter;

/**
 * Writes a Note to disk.
 */
class NoteWriter
{
    /**
     * @var NoteScript\FileWriter An object for writing files to disk
     */
    private $fileWriter;

    /**
     * @var NoteFormatter An object for converting a Note to string
     */
    private $noteFormatter;

    /**
     * @var NoteFilenameFormatter
     */
    private $filenameFormatter;

    /**
     * @param NoteScript\FileWriter $fileWriter
     * @param NoteFormatter $noteFormatter
     * @param NoteFilenameFormatter
     */
    public function __construct(
        FileWriter $fileWriter,
        NoteFormatter $noteFormatter,
        NoteFilenameFormatter $filenameFormatter
    ) {
        $this->fileWriter = $fileWriter;
        $this->noteFormatter = $noteFormatter;
        $this->filenameFormatter = $filenameFormatter;
    }

    /**
     * @param string $destination Location where the note should be stored
     * @param Note $note The note to be written to a file
     * @return string Path to the new note file
     */
    public function writeNote($destination, Note $note)
    {
        return $this->fileWriter->write(
            implode('', [
                $destination,
                DIRECTORY_SEPARATOR,
                $this->filenameFormatter->format($note),
                '.',
                $this->noteFormatter->getExtension(),
            ]),
            $this->noteFormatter->format($note)
        );
    }
}
