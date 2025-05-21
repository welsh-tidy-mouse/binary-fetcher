<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Contract;

use WelshTidyMouse\BinaryFetcher\Exception\BinaryProviderException;
use WelshTidyMouse\BinaryFetcher\Type\OsType;
use WelshTidyMouse\BinaryFetcher\Type\SystemArchType;

interface BinaryProviderInterface
{
    public function getName(): string;

    /**
     * @throws BinaryProviderException
     */
    public function getDownloadableAssetUrl(string $version, OsType $os, SystemArchType $arch): ?string;

    /**
     * @throws BinaryProviderException
     */
    public function getBinaryFilenameFromDownloadedAsset(string $assetFileName, string $downloadDirPath): string;
}
