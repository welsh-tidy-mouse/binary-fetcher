<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use WelshTidyMouse\BinaryFetcher\BinaryFetcher;
use WelshTidyMouse\BinaryFetcher\Contract\BinaryProviderInterface;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryAssetNotFountException;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryAssetUnavailableException;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryProviderServiceException;
use WelshTidyMouse\BinaryFetcher\Exception\NoWritableDirectoryException;
use WelshTidyMouse\BinaryFetcher\Notifier\NotifierInterface;
use WelshTidyMouse\BinaryFetcher\Tests\Fixtures\DummyBinaryProvider;
use WelshTidyMouse\BinaryFetcher\Tool\PlatformDetectorInterface;
use WelshTidyMouse\BinaryFetcher\Type\OsType;
use WelshTidyMouse\BinaryFetcher\Type\SystemArchType;

class BinaryFetcherTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . '/binary_fetcher_test_' . uniqid();
        mkdir($this->tmpDir, 0777, true);
    }

    protected function tearDown(): void
    {
        (new Filesystem())->remove($this->tmpDir);
    }

    public function testThrowsIfDirectoryIsNotWritable(): void
    {
        $provider = $this->createMock(BinaryProviderInterface::class);
        $platform = $this->createMock(PlatformDetectorInterface::class);
        $client = $this->createMock(HttpClientInterface::class);

        $platform->method('getOS')->willReturn(OsType::LINUX);
        $platform->method('getArch')->willReturn(SystemArchType::X_64);

        $fetcher = new BinaryFetcher('/root', $client, $platform);

        $this->expectException(NoWritableDirectoryException::class);
        $fetcher->download($provider);
    }

    public function testThrowsIfAssetUrlIsNull(): void
    {
        $provider = $this->createMock(BinaryProviderInterface::class);
        $platform = $this->createMock(PlatformDetectorInterface::class);
        $client = $this->createMock(HttpClientInterface::class);
        $provider->method('getDownloadableAssetUrl')->willReturn(null);

        $platform->method('getOS')->willReturn(OsType::LINUX);
        $platform->method('getArch')->willReturn(SystemArchType::X_64);

        $fetcher = new BinaryFetcher($this->tmpDir, $client, $platform);

        $this->expectException(BinaryAssetUnavailableException::class);
        $fetcher->download($provider);
    }

    public function testThrowsIfTransportFails(): void
    {
        $provider = new DummyBinaryProvider();

        $platform = $this->createMock(PlatformDetectorInterface::class);
        $platform->method('getOS')->willReturn(OsType::LINUX);
        $platform->method('getArch')->willReturn(SystemArchType::X_64);

        $client = $this->createMock(HttpClientInterface::class);
        $client->method('request')->willThrowException(
            $this->createMock(TransportExceptionInterface::class)
        );

        $fetcher = new BinaryFetcher($this->tmpDir, $client, $platform);

        $this->expectException(BinaryProviderServiceException::class);
        $fetcher->download($provider);
    }

    public function testThrowsIfClientError(): void
    {
        $provider = new DummyBinaryProvider();

        $platform = $this->createMock(PlatformDetectorInterface::class);
        $platform->method('getOS')->willReturn(OsType::LINUX);
        $platform->method('getArch')->willReturn(SystemArchType::X_64);

        $client = $this->createMock(HttpClientInterface::class);
        $exception = $this->createMock(ClientExceptionInterface::class);
        $client->method('request')->willThrowException($exception);

        $fetcher = new BinaryFetcher($this->tmpDir, $client, $platform);

        $this->expectException(BinaryAssetNotFountException::class);
        $fetcher->download($provider);
    }

    public function testSuccessfulDownload(): void
    {
        $assetFileName = 'my-binary.zip';
        $binaryFileName = 'my-binary';

        $provider = new DummyBinaryProvider();

        $notifier = $this->createMock(NotifierInterface::class);
        $notifier->expects($this->once())->method('start')->with('my-binary', 'latest', OsType::LINUX, SystemArchType::X_64);
        $notifier->expects($this->any())->method('progress');
        $notifier->expects($this->once())->method('end')->with($binaryFileName, $this->tmpDir);

        $platform = $this->createMock(PlatformDetectorInterface::class);
        $platform->method('getOS')->willReturn(OsType::LINUX);
        $platform->method('getArch')->willReturn(SystemArchType::X_64);

        $client = new MockHttpClient([
            new MockResponse('BINARY_CONTENT'),
        ]);

        $fetcher = new BinaryFetcher($this->tmpDir, $client, $platform, new Filesystem(), $notifier);

        $result = $fetcher->download($provider);

        $this->assertSame($binaryFileName, $result);
        $this->assertFileExists($this->tmpDir . '/' . $assetFileName);
        $this->assertSame('BINARY_CONTENT', file_get_contents($this->tmpDir . '/' . $assetFileName));
    }
}
