<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Doctrine\DBAL\Types\Type;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

use function is_string;

class EncryptionProviderStorage
{
    public function __construct(
        /** @var iterable<EncryptionProviderInterface> $encryptionProviders */
        #[TaggedIterator(EncryptionProviderInterface::TAG_NAME)]
        private readonly iterable $encryptionProviders,
        private readonly string $key,
        private readonly EncryptionWay $encryptionWay,
    ) {
    }

    public function decrypt(mixed $value): string
    {
        return $this->getSupportedEncryptionProvider()->decrypt($value, $this->key);
    }

    public function encrypt(string $value): string
    {
        return $this->getSupportedEncryptionProvider()->encrypt($value, $this->key);
    }

    public function injectIntoType(): void
    {
        $type = Type::getType(DoctrineEncryptedObject::TYPE_NAME);
        if (!$type instanceof DoctrineEncryptedObject) {
            throw new RuntimeException('Could not get DoctrineEncryptedObject type');
        }
        $type->encryptionProviderStorage = $this;
    }

    public function getSupportedEncryptionProvider(?EncryptionWay $encryptionWay = null): EncryptionProviderInterface
    {
        foreach ($this->encryptionProviders as $encryptionProvider) {
            if ($encryptionProvider->supports($encryptionWay ?? $this->encryptionWay)) {
                return $encryptionProvider;
            }
        }

        throw new RuntimeException("no supported encryption provider \"{$this->encryptionWay}\" found");
    }
}
