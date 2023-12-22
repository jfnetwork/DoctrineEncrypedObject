<?php

namespace Jfnetwork\DoctrineEncryptedObject\Command;

use Jfnetwork\DoctrineEncryptedObject\EncryptionProviderStorage;
use Jfnetwork\DoctrineEncryptedObject\EncryptionWay;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Service\Attribute\Required;

#[AsCommand('jf:doctrine-encrypted-object:create-key')]
class CreateKeyCommand extends Command
{
    #[Required]
    public EncryptionProviderStorage $encryptionProviderStorage;

    protected function configure(): void
    {
        $this->addArgument('encryption-way', InputArgument::REQUIRED, 'sodium, openssl or defuse (deprecated)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $way = EncryptionWay::from($input->getArgument('encryption-way'));

        $encryptionProvider = $this->encryptionProviderStorage->getSupportedEncryptionProvider($way);

        $key = $encryptionProvider->createKey();

        $output->writeln($key);

        return self::SUCCESS;
    }
}
