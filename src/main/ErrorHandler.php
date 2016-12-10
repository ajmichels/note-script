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

use ErrorException;

class ErrorHandler
{

    /**
     * Transforms PHP non-exception errors and warning into exceptions.
     * @param  int     $severity  The level of the error raised, as an integer
     * @param  string  $message   The error message, as a string
     * @param  string  $filename  The filename that the error was raised in, as a string
     * @param  int     $lineNum   The line number the error was raised at, as an integer
     * @return null
     */
    public static function handle($severity, $message, $filename, $lineNum)
    {
        // If the error reporting level is set to none exit the function
        if (error_reporting() == 0) {
            return;
        }

        // If the error being handled is included in the error reporting setting throw exception
        if (error_reporting() & $severity) {
            throw new ErrorException($message, 0, $severity, $filename, $lineNum);
        }
    }

}
