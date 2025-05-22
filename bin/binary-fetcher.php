#!/usr/bin/env php
<?php

use WelshTidyMouse\BinaryFetcher\Console\ConsoleRunner;

if (file_exists(__DIR__ . '/../../../autoload.php')) {
    require_once __DIR__ . '/../../../autoload.php';
} else {
    require_once __DIR__ . '/../vendor/autoload.php';
}

exit(ConsoleRunner::run());
