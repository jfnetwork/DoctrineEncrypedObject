<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineEncryptedObjectBundle extends Bundle
{
    /**
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function boot()
    {
        if (!Type::hasType(DoctrineEncryptedObject::TYPE_NAME)) {
            Type::addType(DoctrineEncryptedObject::TYPE_NAME, DoctrineEncryptedObject::class);
        }

        $this->container->get(KeyManager::class)->setKey();
    }
}
