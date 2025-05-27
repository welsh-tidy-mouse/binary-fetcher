<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Notifier;

use WelshTidyMouse\BinaryFetcher\Type\OsType;
use WelshTidyMouse\BinaryFetcher\Type\SystemArchType;

interface NotifierInterface
{
    public function start(string $name, string $version, OsType $os, SystemArchType $arch): void;

    public function progress(string $assetFileName, int $dlSize, int $dlNow): void;

    public function end(string $binaryFileName, string $downloadDirPath): void;
}
