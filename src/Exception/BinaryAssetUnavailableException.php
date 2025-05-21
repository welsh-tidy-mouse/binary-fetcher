<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Exception;

use Throwable;
use WelshTidyMouse\BinaryFetcher\Type\OsType;
use WelshTidyMouse\BinaryFetcher\Type\SystemArchType;

final class BinaryAssetUnavailableException extends BinaryFetcherException
{
    public function __construct(protected readonly string $version, protected readonly OsType $os, protected readonly SystemArchType $arch, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getOs(): OsType
    {
        return $this->os;
    }

    public function getArch(): SystemArchType
    {
        return $this->arch;
    }
}
