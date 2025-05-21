# Binary Fetcher

[![CI](https://github.com/welsh-tidy-mouse/binary-fetcher/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/yourname/binary-fetcher/actions/workflows/ci.yml)

Tool to download binaries depending on your platform (OS + architecture).

## ✅ Features

- Download from code hosting platform releases (Github, Gitlab)
- Detects platform: `linux`, `macos`, `windows` / `x64`, `arm64`
- Extendable with new binaries
- Works from CLI or as PHP service

## 🚀 Usage

### CLI

```bash
php bin/binary-fetcher <binary> [version]
```

### PHP

```php
$fetcher = new \BinaryFetcher\BinaryFetcher(__DIR__, HttpClient::create());
$path = $fetcher->download('bun-js', 'v1.2.13');
```

## 🧪 Quality

- `composer test`
- `composer lint`
- `composer cs`
- `composer md`
