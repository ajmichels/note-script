<?php

namespace NoteScript\Note;

/**
 * Format a Note object as a string representation.
 */
class NoteFormatter
{
    const DATE_FORMAT = 'Y-m-d h-i-s';
    const EXTENSION = 'md';

    /**
     * @param Note $note The note to be formatted
     * @return string A string representation of the note
     */
    public function format(Note $note)
    {
        return $this->generateNoteHeader($note) . $note->getContent();
    }

    private function generateNoteHeader(Note $note)
    {
        if ($note->getTitle()) {
            return sprintf("# %s\n%s\n\n", $note->getTitle(), $note->getDate()->format(self::DATE_FORMAT));
        }

        return sprintf("# Note %s\n\n", $note->getDate()->format(self::DATE_FORMAT));
    }

    /**
     * @return string The file extension for the type of file this formatter generates
     */
    public function getExtension()
    {
        return self::EXTENSION;
    }
}
