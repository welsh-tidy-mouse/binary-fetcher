<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Tool;

use RuntimeException;
use WelshTidyMouse\BinaryFetcher\Type\OsType;
use WelshTidyMouse\BinaryFetcher\Type\SystemArchType;

readonly class PlatformDetector implements PlatformDetectorInterface
{
    public function getOS(): OsType
    {
        $os = strtolower(\PHP_OS);

        return match ($os) {
            'win' => OsType::WINDOWS,
            'darwin' => OsType::MACOS,
            'linux' => file_exists('/etc/alpine-release') ? OsType::ALPINE_LINUX : OsType::LINUX,
            default => throw new RuntimeException('Unsupported OS: ' . $os),
        };
    }

    public function getArch(): SystemArchType
    {
        $arch = php_uname('m');

        return match (true) {
            str_contains($arch, 'aarch64'), str_contains($arch, 'arm64') => SystemArchType::ARM_64,
            str_contains($arch, 'x86_64'), str_contains($arch, 'amd64'), str_contains($arch, 'i586') => SystemArchType::X_64,
            default => throw new RuntimeException('Unsupported architecture: ' . $arch),
        };
    }
}
