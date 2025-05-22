#!/usr/bin/env php
<?php

use WelshTidyMouse\BinaryFetcher\Console\ConsoleRunner;

if (file_exists(__DIR__ . '/../../../autoload.php')) {
    require_once __DIR__ . '/../../../autoload.php';
    $classMapFile = __DIR__ . '/../../../autoload_classmap.php';
} else {
    require_once __DIR__ . '/../vendor/autoload.php';
    $classMapFile = __DIR__ . '/../vendor/composer/autoload_classmap.php';
}

if (!file_exists($classMapFile)) {
    throw new RuntimeException('Composer classmap file not found. Run "composer dump-autoload".');
}

ConsoleRunner::run(array_keys(require $classMapFile));
