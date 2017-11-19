<?php

namespace NoteScript;

use NoteScript\Note\Note;
use NoteScript\Note\NoteFormatter;
use NoteScript\Note\NoteFilenameFormatter;

/**
 * Writes a Note to disk.
 */
class NoteWriter
{
    /**
     * @var FileWriter An object for writing files to disk
     */
    private $fileWriter;

    /**
     * @var Note\NoteFormatter An object for converting a Note to string
     */
    private $noteFormatter;

    /**
     * @var Note\NoteFilenameFormatter
     */
    private $filenameFormatter;

    /**
     * @param FileWriter $fileWriter
     * @param Note\NoteFormatter $noteFormatter
     * @param Note\NoteFilenameFormatter
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
     * @param Note\Note $note The note to be written to a file
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
