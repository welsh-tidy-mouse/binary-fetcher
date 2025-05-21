<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Exception;

use Throwable;

class BinaryAssetNotFountException extends BinaryFetcherException
{
    public function __construct(protected readonly string $assetName, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getAssetName(): string
    {
        return $this->assetName;
    }
}
