<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Tool;

interface ArchiveProcessorInterface
{
    public function unzip(string $sourceFilename, string $dirPath): void;

    public function untar(string $sourceFilename, string $dirPath): void;
}
