#!/usr/bin/env php
<?php

use WelshTidyMouse\BinaryFetcher\BinaryProvider\BunJsBinaryProvider;
use WelshTidyMouse\BinaryFetcher\BinaryProvider\DartSassBinaryProvider;
use WelshTidyMouse\BinaryFetcher\BinaryProvider\TailwindCssBinaryProvider;
use WelshTidyMouse\BinaryFetcher\Console\ConsoleRunner;

require __DIR__ . '/../vendor/autoload.php';

$classMapFile = __DIR__ . '/../vendor/composer/autoload_classmap.php';

if (!file_exists($classMapFile)) {
    throw new RuntimeException('Composer classmap file not found. Run "composer dump-autoload".');
}

ConsoleRunner::run(array_keys(require $classMapFile));
