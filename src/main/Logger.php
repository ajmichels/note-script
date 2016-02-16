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
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\RotatingFileHandler;

class Logger
{

    private static $instance;

    private $logger;

    private function __construct()
    {
        $this->logger = new Monolog('note-script');

        $this->logger->pushHandler(new SyslogHandler(
            $this->logger->getName(),
            'user',
            Monolog::ERROR
        ));

        $this->logger->pushHandler(new RotatingFileHandler(
            dirname(__DIR__).'/logs/note-script.log',
            10,
            Monolog::DEBUG
        ));

    }

    public static function getInstance()
    {
        return self::$instance ?: (self::$instance = new self());
    }

    public function getMonolog()
    {
        return $this->logger;
    }

    public static function log($level, $message, array $context = array())
    {
        return self::getInstance()->getMonolog()->log($level, $message, $context);
    }

    public static function debug($message, array $context = array())
    {
        return self::getInstance()->getMonolog()->debug($message, $context);
    }

    public static function info($message, array $context = array())
    {
        return self::getInstance()->getMonolog()->info($message, $context);
    }

    public static function notice($message, array $context = array())
    {
        return self::getInstance()->getMonolog()->notice($message, $context);
    }

    public static function warning($message, array $context = array())
    {
        return self::getInstance()->getMonolog()->warning($message, $context);
    }

    public static function error($message, array $context = array())
    {
        return self::getInstance()->getMonolog()->error($message, $context);
    }

    public static function critical($message, array $context = array())
    {
        return self::getInstance()->getMonolog()->critical($message, $context);
    }

    public static function alert($message, array $context = array())
    {
        return self::getInstance()->getMonolog()->alert($message, $context);
    }

    public static function emergency($message, array $context = array())
    {
        return self::getInstance()->getMonolog()->emergency($message, $context);
    }

}
