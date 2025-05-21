<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Notifier;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WelshTidyMouse\BinaryFetcher\Type\OsType;
use WelshTidyMouse\BinaryFetcher\Type\SystemArchType;

class ConsoleNotifier implements NotifierInterface
{
    protected SymfonyStyle $io;

    protected ?ProgressBar $progressBar = null;

    public function __construct(readonly InputInterface $input, readonly OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    public function start(string $name, string $version, OsType $os, SystemArchType $arch): void
    {
        $this->io->title(\sprintf('Downloading %s binary', $name));
        $this->io->write('Try to find a binary asset for the following constraint:');
        $this->io->listing([
            \sprintf('Version: <info>%s</info>', $version),
            \sprintf('OS: <info>%s</info>', $os->value),
            \sprintf('Arch: <info>%s</info>', $arch->value),
        ]);
    }

    public function progress(int $dlSize, int $dlNow): void
    {
        if (null === $this->progressBar && $dlSize > 0) {
            $this->progressBar = $this->io->createProgressBar();
            $this->progressBar->setPlaceholderFormatter('current', fn (ProgressBar $pb) => $this->getHumanFilesize($pb->getProgress()));
            $this->progressBar->setPlaceholderFormatter('max', fn (ProgressBar $pb) => $this->getHumanFilesize($pb->getMaxSteps()));
            $this->progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%% \n⏱️ %elapsed:6s%\n");
            $this->progressBar->setProgressCharacter('📦');
            $this->progressBar->start($dlSize);
        }

        $this->progressBar?->setProgress($dlNow);
    }

    public function end(string $binaryFileName, string $downloadDirPath): void
    {
        $this->io->newLine(2);
        $this->io->success(\sprintf('Binary "%s" is now available in [%s]', $binaryFileName, $downloadDirPath));
    }

    protected function getHumanFilesize(int $bytes, int $decimals = 2): string
    {
        $size = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = (int) floor((\strlen((string) $bytes) - 1) / 3);

        return \sprintf("%.{$decimals}f%s", $bytes / (1024 ** $factor), $size[$factor]);
    }
}
