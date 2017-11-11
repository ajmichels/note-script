<?php

/**
 * Copyright 2015, AJ Michels
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

use Monolog\Logger as Monolog;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\StreamHandler;

/**
 * Writes and manages application logs.
 */
class Logger extends Monolog
{

    /**
     * Constructor
     * Since this class extends Monolog all this constructor is ding is encapsulating the
     * initialization of a logger instance that is specific to this application.
     */
    public function __construct()
    {
        parent::__construct('note-script');

        // Always log errors to the system log
        $this->pushHandler(new SyslogHandler($this->getName(), 'user', Monolog::ERROR));

        // Log to STDOUT based on the verbosity flags
        $stdOutStreamHandler = new StreamHandler('php://stdout', self::getVerbosityFromArgs());
        $stdOutStreamHandler->setFormatter(new LineFormatter("%level_name%: %message% %context% %extra%\n"));
        $this->pushHandler($stdOutStreamHandler);
    }

    /**
     * Determines what the verbosity of the current execution is based on command line arguments.
     * @return int
     */
    public static function getVerbosityFromArgs()
    {
        $args = array_slice($_SERVER['argv'], 1);
        $verbosity = Monolog::WARNING;

        if (false !== array_search('-vvv', $args)) {
            $verbosity = Monolog::DEBUG;
        } elseif (false !== array_search('-vv', $args)) {
            $verbosity = Monolog::INFO;
        } elseif (false !== array_search('-v', $args)) {
            $verbosity = Monolog::NOTICE;
        }

        return $verbosity;
    }
}
