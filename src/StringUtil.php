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

/**
 * Utility class for manipulating strings.
 */
class StringUtil
{

    /**
     * Strip whitespace and most special characters from a string.
     * @param  string  $string  The string to be manipulated
     * @return string           The simplified string
     */
    public static function simplify($string)
    {
        // convert to lower case
        $string = strtolower($string);
        // remove special characters
        $string = preg_replace('/[^a-zA-Z\d\s\\\\\-:]/', '', $string);
        // convert whitespace characters to hyphens
        $string = preg_replace('/[\s\\\\]+/', '-', $string);
        // truncate the string to 235 characters or less
        $string = substr($string, 0, 235);
        // remove any leading or trailing hyphens
        $string = trim($string, '-');

        return $string;
    }
}
