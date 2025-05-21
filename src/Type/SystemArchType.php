<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Type;

enum SystemArchType: string
{
    case ARM_64 = 'arm64';
    case X_64 = 'x64';
}
