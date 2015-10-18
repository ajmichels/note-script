#!/usr/bin/env php
<?php

/**
 * Copyright 2015, AJ Michels
 *
 * This file is part of Note-Script.
 *
 * Note-Script is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Note-Script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Note-Script.  If not, see <http://www.gnu.org/licenses/>.
 */

require dirname(__DIR__) . '/vendor/autoload.php';

set_error_handler('\Ajmichels\NoteScript\Main::handleError');

\Ajmichels\NoteScript\Main::run();
