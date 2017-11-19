<?php

namespace NoteScript;

use NoteScript\Note\Note;
use NoteScript\Note\NoteFormatter;

/**
 * Writes a Note to disk.
 */
class NoteWriter
{
    const DATE_FORMAT = 'Y-m-d h-i-s';

    /**
     * @var FileWriter An object for writing files to disk
     */
    private $fileWriter;

    /**
     * @var Note\NoteFormatter An object for converting a Note to string
     */
    private $noteFormatter;

    /**
     * @var NoteScript\StringSimplifier
     */
    private $stringSimplifier;

    /**
     * @param FileWriter $fileWriter
     * @param Note\NoteFormatter $noteFormatter
     * @param NoteScript\StringSimplifier $stringSimplifier
     */
    public function __construct(
        FileWriter $fileWriter,
        NoteFormatter $noteFormatter,
        StringSimplifier $stringSimplifier
    ) {
        $this->fileWriter = $fileWriter;
        $this->noteFormatter = $noteFormatter;
        $this->stringSimplifier = $stringSimplifier;
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
                $this->generateFileName($note),
                '.',
                $this->noteFormatter->getExtension(),
            ]),
            $this->noteFormatter->format($note)
        );
    }

    private function generateFileName(Note $note)
    {
        $fileName = str_replace(' ', '-', $note->getDate()->format(self::DATE_FORMAT));

        if ($note->getTitle()) {
            $fileName .= '_' . $this->stringSimplifier->simplify($note->getTitle());
        }

        return $fileName;
    }
}
