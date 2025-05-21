<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Tool;

trait GithubProviderHelperTrait
{
    protected const string GITHUB_DOWNLOAD_LATEST_URL = 'https://github.com/%s/releases/latest/download/%s';

    protected const string GITHUB_DOWNLOAD_VERSION_URL = 'https://github.com/%s/releases/download/%s/%s';

    public function generateDownloadUrl(string $version, string $repository, string $assetName): string
    {
        return 'latest' === $version
            ? \sprintf(self::GITHUB_DOWNLOAD_LATEST_URL, $repository, $assetName)
            : \sprintf(self::GITHUB_DOWNLOAD_VERSION_URL, $repository, $version, $assetName);
    }
}
