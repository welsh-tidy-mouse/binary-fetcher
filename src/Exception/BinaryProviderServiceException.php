<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Exception;

use Throwable;

final class BinaryProviderServiceException extends BinaryFetcherException
{
    public function __construct(protected readonly string $url, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
