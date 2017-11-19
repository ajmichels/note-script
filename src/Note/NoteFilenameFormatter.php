<?php

namespace NoteScript\Note;

use NoteScript\StringSimplifier;

/**
 * Generates a filename based on a Note's data
 */
class NoteFilenameFormatter
{
    const DATE_FORMAT = 'Y-m-d h-i-s';

    /**
     * @var NoteScript\StringSimplifier
     */
    private $stringSimplifier;

    /**
     * @param NoteScript\StringSimplifier $stringSimplifier
     */
    public function __construct(StringSimplifier $stringSimplifier)
    {
        $this->stringSimplifier = $stringSimplifier;
    }

    /**
     * @param Note $note Note to create a filename for.
     * @return @string The filename for the Note
     */
    public function format(Note $note)
    {
        $fileName = str_replace(' ', '-', $note->getDate()->format(self::DATE_FORMAT));

        if ($note->getTitle()) {
            $fileName .= '_' . $this->stringSimplifier->simplify($note->getTitle());
        }

        return $fileName;
    }
}
