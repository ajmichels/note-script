<?php

namespace NoteScript;

use PHPUnit_Framework_TestCase as TestCase;

class NoteWriterTest extends TestCase
{
    const PATH = '/tmp/path';
    const FILENAME = 'bar';
    const EXTENSION = 'md';
    const CONTENT = 'foo';

    private $mockFileWriter;
    private $mockNoteFormatter;
    private $mockNoteFilenameFormatter;
    private $expectedPath;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->mockFileWriter = $this->getMockBuilder(FileWriter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockFileWriter->method('write')->will($this->returnArgument(0));
        $this->mockNoteFormatter = $this->getMockBuilder(Note\NoteFormatter::class)
            ->getMock();
        $this->mockNoteFormatter->method('getExtension')->willReturn(self::EXTENSION);
        $this->mockNoteFilenameFormatter = $this->getMockBuilder(Note\NoteFilenameFormatter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockNoteFilenameFormatter->method('format')->willReturn(self::FILENAME);

        $this->noteWriter = new NoteWriter(
            $this->mockFileWriter,
            $this->mockNoteFormatter,
            $this->mockNoteFilenameFormatter
        );

        $this->expectedPath = sprintf(
            '%s/%s.%s',
            self::PATH,
            self::FILENAME,
            self::EXTENSION
        );
    }

    /**
     * @test
     */
    public function writeNotePassesPathAndContentToFileWriter()
    {
        $this->mockNoteFormatter->method('format')->willReturn(self::CONTENT);

        $this->mockFileWriter->expects($this->once())->method('write')->with($this->expectedPath, self::CONTENT);
        $this->noteWriter->writeNote(self::PATH, new Note\Note());
    }

    /**
     * @test
     */
    public function writeNoteReturnsPath()
    {
        $this->assertEquals($this->expectedPath, $this->noteWriter->writeNote(self::PATH, new Note\Note()));
    }
}
