<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Doctrine\DBAL\Types\Type;
use RuntimeException;

class KeyManager
{
    public function __construct(
        private readonly string $key,
    ) {
    }

    /**
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Doctrine\DBAL\Exception
     */
    public function setKey(): void
    {
        $type = Type::getType(DoctrineEncryptedObject::TYPE_NAME);
        if (!$type instanceof DoctrineEncryptedObject) {
            throw new RuntimeException('Could not get DoctrineEncryptedObject type');
        }
        $type->setKey($this->key);
    }
}
