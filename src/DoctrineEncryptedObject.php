<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Defuse\Crypto\Key;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use RuntimeException;

use function count_chars;
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

    private ?Key $key = null;

    /**
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function setKey(string $key): void
    {
        $this->key = Key::loadFromAsciiSafeString($key);
    }

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
     * @throws \RuntimeException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Random\RandomException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        $this->assertKeyWasSet();

        $randomGarbageLength = random_int(64, 255);
        $randomGarbage = random_bytes($randomGarbageLength);
        return Crypto::encrypt(
            pack('Ca*', $randomGarbageLength, $randomGarbage) . serialize($value),
            $this->key,
            true,
        );
    }

    /**
     * @throws \RuntimeException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        $this->assertKeyWasSet();

        if (!$value) {
            return null;
        }

        $decodedString = Crypto::decrypt(
            $value,
            $this->key,
            count_chars('0123456789abcdef' . $value, 3) !== '0123456789abcdef',
        );

        $randomGarbageLength = ord($decodedString[0]);
        $decodedString = substr($decodedString, $randomGarbageLength + 1);

        return unserialize($decodedString, ['allowed_classes' => true]);
    }

    /**
     * @throws \RuntimeException
     */
    private function assertKeyWasSet(): void
    {
        if (!$this->key) {
            throw new RuntimeException('encrypt key was not set');
        }
    }
}
