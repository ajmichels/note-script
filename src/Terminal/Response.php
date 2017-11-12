<?php

namespace NoteScript\Terminal;

use \InvalidArgumentException;

/**
 * This class represents a response to a terminal command. Instances
 * of this class are immutable. Changing a value of the response requires
 * creation of a new instance.
 */
class Response
{
    private $returnCode;
    private $output;
    private $error;

    /**
     * @param int $returnCode
     * @param string $output
     * @param string $error
     */
    public function __construct($returnCode = 0, $output = null, $error = null)
    {
        $this->setInteger('returnCode', $returnCode);
        $this->setString('output', $output);
        $this->setString('error', $error);
    }

    public function getReturnCode()
    {
        return $this->returnCode;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getError()
    {
        return $this->error;
    }

    private function setInteger($property, $value)
    {
        if (!is_int($value)) {
            throw new InvalidArgumentException(sprintf('$%s must be an integer', $property));
        }

        $this->$property = $value;
    }

    private function setString($property, $value)
    {
        if (!is_null($value) && !is_string($value)) {
            throw new InvalidArgumentException(sprintf('$%s must be a string', $property));
        }

        $this->$property = $value;
    }
}
