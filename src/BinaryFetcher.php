<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use WelshTidyMouse\BinaryFetcher\Contract\BinaryProviderInterface;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryAssetNotFountException;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryAssetUnavailableException;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryProviderServiceException;
use WelshTidyMouse\BinaryFetcher\Exception\NoWritableDirectoryException;
use WelshTidyMouse\BinaryFetcher\Notifier\NotifierInterface;
use WelshTidyMouse\BinaryFetcher\Tool\PlatformDetector;
use WelshTidyMouse\BinaryFetcher\Tool\PlatformDetectorInterface;

readonly class BinaryFetcher implements BinaryFetcherInterface
{
    public const array DEFAULT_HEADERS = [
        'Accept' => 'application/octet-stream',
        'User-Agent' => 'BinaryFetcher',
    ];

    public function __construct(
        protected string $downloadDirPath,
        protected HttpClientInterface $httpClient,
        protected PlatformDetectorInterface $platformDetector = new PlatformDetector(),
        protected Filesystem $filesystem = new Filesystem(),
        protected ?NotifierInterface $notifier = null,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function download(
        BinaryProviderInterface $binaryProvider,
        string $version = 'latest',
    ): ?string {
        $os = $this->platformDetector->getOS();
        $arch = $this->platformDetector->getArch();

        if (!is_writable($this->downloadDirPath)) {
            throw new NoWritableDirectoryException();
        }

        $this->notifier?->start($binaryProvider->getName(), $version, $os, $arch);

        $downlodableAssetUrl = $binaryProvider->getDownloadableAssetUrl($version, $os, $arch);
        if (null === $downlodableAssetUrl) {
            throw new BinaryAssetUnavailableException($version, $os, $arch);
        }

        try {
            $assetFileName = basename($downlodableAssetUrl);
            $response = $this->httpClient->request('GET', $downlodableAssetUrl, [
                'headers' => self::DEFAULT_HEADERS,
                'on_progress' => fn (int $dlNow, int $dlSize) => $this->notifier?->progress($assetFileName, $dlSize, $dlNow),
            ]);

            $this->filesystem->dumpFile(Path::join($this->downloadDirPath, $assetFileName), $response->getContent());
        } catch (RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw new BinaryProviderServiceException($downlodableAssetUrl);
        } catch (ClientExceptionInterface) {
            throw new BinaryAssetNotFountException($assetFileName);
        }

        $binaryFileName = $binaryProvider->getBinaryFilenameFromDownloadedAsset($assetFileName, $this->downloadDirPath);

        $this->notifier?->end($binaryFileName, $this->downloadDirPath);

        return $binaryFileName;
    }
}
