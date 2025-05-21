<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Tool;

use PharData;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use ZipArchive;

readonly class ArchiveProcessor implements ArchiveProcessorInterface
{
    public function __construct(protected Filesystem $filesystem = new Filesystem())
    {
    }

    public function unzip(string $sourceFilename, string $dirPath): void
    {
        $archive = new ZipArchive();
        $archive->open(Path::join($dirPath, $sourceFilename));
        $archive->extractTo($dirPath);
        $archive->close();
    }

    public function untar(string $sourceFilename, string $dirPath): void
    {
        $archive = new PharData(Path::join($dirPath, $sourceFilename));
        $archive->decompress();
        $archive->extractTo($dirPath);
    }
}
