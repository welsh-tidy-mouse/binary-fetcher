<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Tests\Fixtures;

use WelshTidyMouse\BinaryFetcher\Contract\BinaryProviderInterface;
use WelshTidyMouse\BinaryFetcher\Type\OsType;
use WelshTidyMouse\BinaryFetcher\Type\SystemArchType;

final class DummyBinaryProvider implements BinaryProviderInterface
{
    public function __construct()
    {
    }

    public static function getName(): string
    {
        return 'my-binary';
    }

    public function getDownloadableAssetUrl(string $version, OsType $os, SystemArchType $arch): string
    {
        return 'https://example.com/my-binary.zip';
    }

    public function getBinaryFilenameFromDownloadedAsset(string $assetPath, string $outputDir): string
    {
        return 'my-binary';
    }
}
