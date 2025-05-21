<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Tool;

use WelshTidyMouse\BinaryFetcher\Type\OsType;
use WelshTidyMouse\BinaryFetcher\Type\SystemArchType;

interface PlatformDetectorInterface
{
    public function getOS(): OsType;

    public function getArch(): SystemArchType;
}
