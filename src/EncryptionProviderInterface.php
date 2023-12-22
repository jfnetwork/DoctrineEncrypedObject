<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::TAG_NAME)]
interface EncryptionProviderInterface
{
    public const TAG_NAME = 'doctrine_encrypted_object.encryption_provider';

    public function decrypt(mixed $value, string $key): string;

    public function encrypt(string $value, string $key): string;

    public function supports(EncryptionWay $encryptionWay): bool;

    public function createKey(): string;
}
