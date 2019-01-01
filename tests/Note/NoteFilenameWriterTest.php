<?php

namespace NoteScript\Note;

use NoteScript\StringSimplifier;
use PHPUnit\Framework\TestCase;

class NoteFilenameFormatterTest extends TestCase
{
    private $mockStringSimplifier;
    private $filenameFormatter;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->mockStringSimplifier = $this->getMockBuilder(StringSimplifier::class)->getMock();
        $this->filenameFormatter = new NoteFilenameFormatter($this->mockStringSimplifier);
    }

    /**
     * @test
     */
    public function format()
    {
        $note = new Note(null, null, new \DateTime('2017-01-02T03:04:05'));
        $this->assertEquals('2017-01-02-03-04-05', $this->filenameFormatter->format($note));
    }

    /**
     * @test
     */
    public function formatWithTitle()
    {
        $this->mockStringSimplifier->method('simplify')->will($this->returnArgument(0));
        $note = new Note('title', null, new \DateTime('2017-01-02T03:04:05'));
        $this->assertEquals('2017-01-02-03-04-05_title', $this->filenameFormatter->format($note));
    }
}
