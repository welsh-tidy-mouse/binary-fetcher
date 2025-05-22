<?php

declare(strict_types=1);

namespace WelshTidyMouse\BinaryFetcher\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use WelshTidyMouse\BinaryFetcher\BinaryFetcher;
use WelshTidyMouse\BinaryFetcher\Contract\BinaryProviderInterface;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryAssetNotFountException;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryAssetUnavailableException;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryProviderException;
use WelshTidyMouse\BinaryFetcher\Exception\BinaryProviderServiceException;
use WelshTidyMouse\BinaryFetcher\Exception\UnknowBinaryProviderException;
use WelshTidyMouse\BinaryFetcher\Notifier\ConsoleNotifier;

class DownloadCommand extends Command
{
    /**
     * @param array<string,BinaryProviderInterface> $providers
     */
    public function __construct(private readonly array $providers = [])
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('download')
            ->setDescription('Download binary')
            ->addArgument('binary', InputArgument::REQUIRED, 'Binary name')
            ->addArgument('version', InputArgument::OPTIONAL, 'Binary version', 'latest')
            ->addOption('dir', 'd', InputOption::VALUE_OPTIONAL, 'Download dir path', getcwd())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $binaryProvdier = $this->getBinaryProvider($input->getArgument('binary'));

            $fetcher = new BinaryFetcher($input->getOption('dir'), HttpClient::create(), notifier: new ConsoleNotifier($input, $output));
            $fetcher->download($binaryProvdier, $input->getArgument('version'));
            $io->block('Thank you sweet lil\' creature 🐭', style: 'fg=yellow');

            return Command::SUCCESS;
        } catch (UnknowBinaryProviderException $e) {
            $io->error(\sprintf('There is no binary asset provider for %s', $e->getProviderName()));

            return Command::FAILURE;
        } catch (BinaryAssetUnavailableException $e) {
            $io->error(\sprintf(
                'There is no binary asset found for the given requirements: version "%s", os "%s" and arch "%s"',
                $e->getVersion(),
                $e->getOs()->value,
                $e->getArch()->value
            ));

            return Command::FAILURE;
        } catch (BinaryAssetNotFountException $e) {
            $io->error(\sprintf('The "%s" binary asset has not been found on provider', $e->getAssetName()));

            return Command::FAILURE;
        } catch (BinaryProviderServiceException $e) {
            $io->error(\sprintf('The  binary asset provider service failed to response "%s"', $e->getUrl()));

            return Command::FAILURE;
        } catch (BinaryProviderException $e) {
            $io->error(\sprintf('The  binary asset provider "%s" encountered the follogin error: %s', $e->getName(), $e->getMessage()));

            return Command::FAILURE;
        }
    }

    protected function getBinaryProvider(string $providerNameOrClass): BinaryProviderInterface
    {
        if (class_exists($providerNameOrClass)) {
            /** @var class-string<BinaryProviderInterface> $providerNameOrClass */
            return new $providerNameOrClass();
        }

        return
            $this->providers[$providerNameOrClass]
            ?? throw new UnknowBinaryProviderException($providerNameOrClass);
    }
}
