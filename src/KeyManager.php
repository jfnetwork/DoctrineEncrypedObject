<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Doctrine\DBAL\Types\Type;

class KeyManager
{

    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function setKey()
    {
        /** @var DoctrineEncryptedObject $type */
        $type = Type::getType(DoctrineEncryptedObject::TYPE_NAME);
        $type->setKey($this->key);
    }
}
