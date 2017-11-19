<?php

namespace NoteScript;

use PHPUnit_Framework_TestCase as TestCase;

class NoteWriterTest extends TestCase
{
    /**
     * @test
     */
    public function passesFormattedNoteToFileWriter()
    {
        $mockFileWriter = $this->getMockBuilder(FileWriter::class)->disableOriginalConstructor()->getMock();
        $mockNoteFormatter = $this->getMockBuilder(Note\NoteFormatter::class)->getMock();
        $mockNoteFormatter->method('format')->willReturn('foo');
        $mockNoteFormatter->method('getExtension')->willReturn('md');
        $mockStringSimplifier = $this->getMockBuilder(StringSimplifier::class)->getMock();
        $noteWriter = new NoteWriter($mockFileWriter, $mockNoteFormatter, $mockStringSimplifier);

        $mockFileWriter->expects($this->once())->method('write')->with('/tmp/path/2017-01-02-03-04-05.md', 'foo');

        $noteWriter->writeNote('/tmp/path', new Note\Note(null, null, new \DateTime('2017-01-02T03:04:05')));
    }
}
