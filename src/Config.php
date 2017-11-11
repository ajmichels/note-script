<?php

/**
 * Copyright 2016, AJ Michels
 *
 * This file is part of Note-Script.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if
 * not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace NoteScript;

use ArrayAccess;

/**
 * Initializes and stores application configuration data.
 */
class Config implements ArrayAccess
{

    const NOTE_DIR = 'noteDir';
    const LOG_DIR = 'logDir';
    const ENV_VAR_HOME = 'HOME';
    const ENV_VAR_NOTE_HOME = 'NOTE_HOME';
    const DEFAULT_NOTE_DIR = '/notes';

    /**
     * Collection of configuration key/value pairs
     * @var array
     */
    private $values = [];

    /**
     * Constructor method
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * Factory method for creating new instances of Config.
     * @return NoteScript\Config A newly created instance of Config
     */
    public static function create()
    {
        $configValues = [];
        $configValues[self::NOTE_DIR] = self::getNoteDirectory();
        $configValues[self::LOG_DIR] = dirname(dirname(__DIR__)) . '/logs';
        return new Config($configValues);
    }

    /**
     * Determine if a specific config value exists
     * @param  mixed $offset The key for the config value.
     * @return boolean       True if config value exists.
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * Retrieve a configuration value by key.
     * @param  mixed      $offset The key for the value being fetched.
     * @return mixed|null         The value or null
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->values[$offset] : null;
    }

    /**
     * Required by the ArrayAccess interface, but in this case will throw an exception as config
     * values are read only.
     * @param  mixed $offset They key for the value
     * @param  mixed $value  The value being set
     * @throws NoteScript\ConfigException
     * @return null
     */
    public function offsetSet($offset, $value)
    {
        throw new ConfigException(ConfigException::MSG_READ_ONLY, [$offset, $value]);
    }

    /**
     * Required by the ArrayAccess interface, but in this case will throw an exception as config
     * values are read only and cannot be removed once set.
     * @param  mixed $offset The key of the value to be removed
     * @throws NoteScript\ConfigException
     * @return null
     */
    public function offsetUnset($offset)
    {
        throw new ConfigException(ConfigException::MSG_NOT_REMOVABLE, [$offset]);
    }

    /**
     * Retrieves the directory for storing notes by reading certain environment variables.
     * @throws NoteScript\ConfigException
     * @return string Path to directory
     */
    private static function getNoteDirectory()
    {
        $noteDir = getenv(self::ENV_VAR_NOTE_HOME);

        if ($noteDir === false || trim($noteDir) === '') {
            $homeDir = getenv(self::ENV_VAR_HOME);

            if ($homeDir === false || trim($homeDir) === '') {
                $msg = sprintf(ConfigException::MSG_MISSING_ENV_VAR, self::ENV_VAR_HOME);
                throw new ConfigException($msg);
            }

            $noteDir = $homeDir . self::DEFAULT_NOTE_DIR;
        }

        return $noteDir;
    }
}
