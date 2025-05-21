<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Exception;

use Throwable;

final class BinaryProviderException extends BinaryFetcherException
{
    public function __construct(protected readonly string $name, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
