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

use RuntimeException;

/**
 * Exceptions that are thrown when there are problems with the application configuration.
 */
class ConfigException extends RuntimeException
{
    const MSG_READ_ONLY = 'Config values are read only.';
    const MSG_NOT_REMOVABLE = 'Config values cannot be removed.';
    const MSG_MISSING_ENV_VAR = 'There is no $%s environment variable defined.';

    private $data;

    public function __construct($message, $data = [], $code = 0, \Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }
}
