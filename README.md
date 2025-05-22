# Binary Fetcher

[![CI](https://github.com/welsh-tidy-mouse/binary-fetcher/actions/workflows/pipeline.yml/badge.svg)](https://github.com/welsh-tidy-mouse/binary-fetcher/actions/workflows/pipeline.yml)
[![Binary Fetcher license](https://img.shields.io/github/license/welsh-tidy-mouse/binary-fetcher?public)](https://github.com/welsh-tidy-mouse/binary-fetcher/blob/master/LICENSE)

Tool to download binaries depending on your platform (OS + architecture).

## ✅ Features

- Download from code hosting platform releases (Github, Gitlab, ...)
- Detects platform: `linux`, `macos`, `windows` / `x64`, `arm64`
- Works from CLI or as PHP service with binary providers

## 🔧 Install

```bash
composer require welsh-tidy-mouse/binary-fetcher
```

## 🚀 Usage

### CLI

```bash
php bin/binary-fetcher "\MyVendo\BinaryProvider\MyBinaryProvider" [version] [--dir="/my/download/dir"]
```

### PHP

```php
$fetcher = new \BinaryFetcher\BinaryFetcher('/my/download/dir', HttpClient::create());
$binaryName = $fetcher->download(new \MyVendo\BinaryProvider\MyBinaryProvider, 'v1.2.13');
```

## 🧪 Quality

- `composer test`
- `composer lint`
- `composer cs`
- `composer md`
- `composer check` for all commands above
