#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/vendor/autoload.php';

set_error_handler('\Ajmichels\NoteScript\Main::handleError');

\Ajmichels\NoteScript\Main::run();
