<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher;

use WelshTidyMouse\BinaryFetcher\Contract\BinaryProviderInterface;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryAssetUnavailableException;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryProviderException;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryProviderServiceException;

interface BinaryFetcherInterface
{
    /**
     * @throws BinaryProviderServiceException
     * @throws BinaryAssetUnavailableException
     * @throws BinaryProviderException
     */
    public function download(
        BinaryProviderInterface $binaryProvider,
        string $version = 'latest',
    ): ?string;
}
