<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Console;

use Exception;
use ReflectionClass;
use Symfony\Component\Console\Application;
use Throwable;
use WelshTidyMouse\BinaryFetcher\Console\Command\DownloadCommand;
use WelshTidyMouse\BinaryFetcher\Contract\BinaryProviderInterface;

final class ConsoleRunner
{
    /**
     * @param string[] $classmap
     *
     * @throws Exception
     */
    public static function run(array $classmap): void
    {
        $providers = self::getBinaryProviders($classmap);
        $cli = new Application('Binary Fetch Command Line Interface', 'v1.0.0');
        $cli->addCommands([new DownloadCommand($providers)]);
        $cli->run();
    }

    /**
     * @param string[] $classmap
     *
     * @return array<string,BinaryProviderInterface>
     */
    private static function getBinaryProviders(array $classmap): array
    {
        $providers = [];

        foreach ($classmap as $class) {
            if (!class_exists($class) || !\in_array(BinaryProviderInterface::class, class_implements($class), true)) {
                continue;
            }

            try {
                $reflection = new ReflectionClass($class);
                /** @var BinaryProviderInterface $instance */
                $instance = $reflection->newInstance();
                $providers[$instance->getName()] = $instance;
            } catch (Throwable) {
                continue;
            }
        }

        return $providers;
    }
}
