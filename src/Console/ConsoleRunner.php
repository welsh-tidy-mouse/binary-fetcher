<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Console;

use Exception;
use Symfony\Component\Console\Application;
use WelshTidyMouse\BinaryFetcher\Console\Command\DownloadCommand;

final class ConsoleRunner
{
    /**
     * @throws Exception
     */
    public static function run(): void
    {
        $cli = new Application('Binary Fetch Command Line Interface', 'v1.0.0');
        $cli->addCommands([new DownloadCommand()]);
        $cli->run();
    }
}
