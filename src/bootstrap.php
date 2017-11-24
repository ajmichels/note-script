<?php

use NoteScript\FileWriter;
use NoteScript\Logger;
use NoteScript\Main;
use NoteScript\Note\NoteFilenameFormatter;
use NoteScript\Note\NoteFormatter;
use NoteScript\Note\NoteWriter;
use NoteScript\StringSimplifier;

$factories = [
    Logger::class => function () {
        return new Logger();
    },
    FileWriter::class => function ($container) {
        return new FileWriter(
            $container->get(Logger::class)
        );
    },
    NoteFormatter::class => function () {
        return new NoteFormatter();
    },
    StringSimplifier::class => function () {
        return new StringSimplifier();
    },
    NoteFilenameFormatter::class => function ($container) {
        return new NoteFilenameFormatter(
            $container->get(StringSimplifier::class)
        );
    },
    NoteWriter::class => function ($container) {
        return new NoteWriter(
            $container->get(FileWriter::class),
            $container->get(NoteFormatter::class),
            $container->get(NoteFilenameFormatter::class)
        );
    },
    Main::class => function ($container) {
        return new Main(
            $container->get(NoteWriter::class),
            $container->get(Logger::class)
        );
    },
];

foreach ($factories as $name => $factory) {
    $container->registerFactory($name, $factory);
}
