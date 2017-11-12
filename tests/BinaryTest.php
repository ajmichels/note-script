<?php

namespace NoteScript;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * Testing the binary file as the final point of failure.
 * @group system
 */
class BinaryTest extends TestCase
{
    const NOTE_DIR = '/tmp/notescript-note-dir';
    const BINARY = 'bin/note';
    const TEST_TITLE = 'Foo Bar';
    const TEST_CONTENT = 'The quick brown fox jumped over the lazy dog.';

    /**
     * @after
     */
    public function cleanUp()
    {
        $this->removeTempFilesAndDirectory();
    }

    private function removeTempFilesAndDirectory()
    {
        if (is_dir(self::NOTE_DIR)) {
            $files = scandir(self::NOTE_DIR);

            foreach ($files as $file) {
                if (!in_array($file, ['.','..'])) {
                    unlink(self::NOTE_DIR . DIRECTORY_SEPARATOR . $file);
                }
            }

            rmdir(self::NOTE_DIR);
        }
    }

    /**
     * @test
     */
    public function createsFileReturnsSuccessCode()
    {
        $this->assertEquals(0, $this->createFile()[0]);
    }

    /**
     * @test
     */
    public function createsFileCreatesAFile()
    {
        $this->assertFileExists(trim($this->createFile()[1]));
    }

    private function createFile()
    {
        return $this->executeCommand(sprintf('NOTE_HOME=%s %s', self::NOTE_DIR, self::BINARY));
    }

    /**
     * @test
     */
    public function createFileWithTitleReturnsSuccessCode()
    {
        $this->assertEquals(0, $this->createFileWithTitle(self::TEST_TITLE)[0]);
    }

    /**
     * @test
     */
    public function createFileWithTitleCreatesAFile()
    {
        $this->assertFileExists($this->createFileWithTitle(self::TEST_TITLE)[1]);
    }

    /**
     * @test
     */
    public function createFileWithTitleCreatesFileWithCorrectName()
    {
        $this->assertRegExp(
            '/[0-9]{4}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}_foo-bar\.md/',
            basename($this->createFileWithTitle(self::TEST_TITLE)[1])
        );
    }

    private function createFileWithTitle($title)
    {
        return $this->executeCommand(sprintf(
            'NOTE_HOME=%s %s -t "%s"',
            self::NOTE_DIR,
            self::BINARY,
            $title
        ));
    }

    /**
     * @test
     */
    public function createFileWithContent()
    {
        $file = file_get_contents($this->createFileWithInput(self::TEST_CONTENT)[1]);
        $this->assertThat($file, $this->stringContains(self::TEST_CONTENT));
    }

    /**
     * @test
     */
    public function fileCreatedWithContentEndsWithNewline()
    {
        $file = file_get_contents($this->createFileWithInput(self::TEST_CONTENT)[1]);
        $this->assertStringEndsWith(PHP_EOL, $file);
    }

    private function createFileWithInput($input)
    {
        return $this->executeCommand(sprintf(
            'echo "%s" | NOTE_HOME=%s %s',
            $input,
            self::NOTE_DIR,
            self::BINARY
        ));
    }

    private function executeCommand($command)
    {
        ob_start();
        passthru($command, $returnCode);
        return [$returnCode, ob_get_clean()];
    }
}
