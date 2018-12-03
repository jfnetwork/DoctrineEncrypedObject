<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use RuntimeException;

class DoctrineEncryptedObject extends Type
{
    const TYPE_NAME = 'encrypted_object';

    /** @var Key */
    private $key;

    /**
     * @param string $key
     *
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function setKey(string $key)
    {
        $this->key = Key::loadFromAsciiSafeString($key);
    }

    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return mixed|string
     * @throws \RuntimeException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $this->assertKeyWasSet();

        $randomGarbageLength = \random_int(64, 255);
        $randomGarbage = \random_bytes($randomGarbageLength);
        return Crypto::encrypt(
            \pack('Ca*', $randomGarbageLength, $randomGarbage).\serialize($value),
            $this->key
        );
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return mixed|null
     *
     * @throws \RuntimeException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $this->assertKeyWasSet();

        if (!$value) {
            return null;
        }

        $decodedString = Crypto::decrypt($value, $this->key);

        $randomGarbageLength = \ord($decodedString[0]);
        $decodedString = \substr($decodedString, $randomGarbageLength + 1);

        /** @noinspection UnserializeExploitsInspection */
        return \unserialize($decodedString);
    }

    /**
     * @throws \RuntimeException
     */
    private function assertKeyWasSet()
    {
        if (!$this->key) {
            throw new RuntimeException('encrypt key was not set');
        }
    }
}
