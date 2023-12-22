<?php

namespace Jfnetwork\DoctrineEncryptedObject\EncryptionProvider;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use JetBrains\PhpStorm\Deprecated;
use Jfnetwork\DoctrineEncryptedObject\EncryptionProviderInterface;
use Jfnetwork\DoctrineEncryptedObject\EncryptionWay;

use function class_exists;

#[Deprecated]
class Defuse implements EncryptionProviderInterface
{
    public function decrypt(mixed $value, string $key): string
    {
        return Crypto::decrypt(
            $value,
            $this->getKey($key),
            count_chars('0123456789abcdef' . $value, 3) !== '0123456789abcdef',
        );
    }

    public function encrypt(string $value, string $key): string
    {
        return Crypto::encrypt(
            $value,
            $this->getKey($key),
            true,
        );
    }

    public function supports(EncryptionWay $encryptionWay): bool
    {
        return EncryptionWay::Defuse === $encryptionWay && class_exists(Crypto::class);
    }

    private function getKey(string $key)
    {
        static $keys = [];
        $keys[$key] ??= Key::loadFromAsciiSafeString($key);

        return $keys[$key];
    }

    public function createKey(): string
    {
        return Key::createNewRandomKey()->saveToAsciiSafeString();
    }
}
