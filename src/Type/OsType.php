<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Type;

enum OsType: string
{
    case WINDOWS = 'windows';
    case MACOS = 'macos';
    case LINUX = 'linux';
    case ALPINE_LINUX = 'alpine-linux';
}
