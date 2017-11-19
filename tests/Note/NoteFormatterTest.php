<?php

namespace NoteScript\Note;

use DateTime;
use PHPUnit_Framework_TestCase as TestCase;

class NoteFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function formatsNote()
    {
        $note = new Note(null, 'foo', new DateTime('2017-01-02T03:04:05'));
        $formattedNote = (new NoteFormatter())->format($note);
        $this->assertEquals("# Note 2017-01-02 03-04-05\n\nfoo", $formattedNote);
    }

    /**
     * @test
     */
    public function formatsNoteWithTitle()
    {
        $note = new Note('foo', 'bar', new DateTime('2017-01-02T03:04:05'));
        $formattedNote = (new NoteFormatter())->format($note);
        $this->assertEquals("# foo\n2017-01-02 03-04-05\n\nbar", $formattedNote);
    }
}
