<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Doctrine\DBAL\Types\Type;
use RuntimeException;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineEncryptedObjectBundle extends Bundle
{
    /**
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Doctrine\DBAL\Exception
     */
    public function boot(): void
    {
        if (!Type::hasType(DoctrineEncryptedObject::TYPE_NAME)) {
            Type::addType(DoctrineEncryptedObject::TYPE_NAME, DoctrineEncryptedObject::class);
        }

        $keyManager = $this->container->get(KeyManager::class);
        if (!$keyManager instanceof KeyManager) {
            throw new RuntimeException('Could not find KeyManager service');
        }
        $keyManager->setKey();
    }
}
