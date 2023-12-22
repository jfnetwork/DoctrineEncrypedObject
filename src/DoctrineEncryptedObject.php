<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

use function ord;
use function pack;
use function random_bytes;
use function random_int;
use function serialize;
use function substr;
use function unserialize;

class DoctrineEncryptedObject extends Type
{
    public const TYPE_NAME = 'encrypted_object';

    public EncryptionProviderStorage $encryptionProviderStorage;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return sprintf(
            "%s COMMENT '(DC2Type:%s)'",
            $platform->getBlobTypeDeclarationSQL($column),
            self::TYPE_NAME,
        );
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @throws \Random\RandomException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        $randomGarbageLength = random_int(64, 255);
        $randomGarbage = random_bytes($randomGarbageLength);

        return $this->encryptionProviderStorage->encrypt(
            pack('Ca*', $randomGarbageLength, $randomGarbage) . serialize($value),
        );
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if (!$value) {
            return null;
        }

        $decodedString = $this->encryptionProviderStorage->decrypt($value);

        $randomGarbageLength = ord($decodedString[0]);
        $decodedString = substr($decodedString, $randomGarbageLength + 1);

        return unserialize($decodedString, ['allowed_classes' => true]);
    }
}
