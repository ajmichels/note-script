<?php

namespace NoteScript;

class FileException extends \Exception
{
    private $path;

    /**
     * @param string $msg
     * @param string $path The filepath the exception occured on.
     * @param int $code
     * @param Throwable $previous
     */
    public function __construct($msg, $path, $code = 0, \Throwable $previous = null)
    {
        $this->path = $path;
        parent::__construct($msg, $code, $previous);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function __toString()
    {
        $msg = $this->getMessage();
        return str_replace(parent::__toString(), $msg, sprintf('%s (%s)', $msg, $this->path));
    }
}
