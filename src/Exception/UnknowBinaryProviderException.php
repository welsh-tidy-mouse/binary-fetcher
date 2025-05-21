<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Exception;

use Throwable;

final class UnknowBinaryProviderException extends BinaryFetcherException
{
    public function __construct(protected readonly string $providerName, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getProviderName(): string
    {
        return $this->providerName;
    }
}
