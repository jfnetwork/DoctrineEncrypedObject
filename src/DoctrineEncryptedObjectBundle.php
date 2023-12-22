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
     * @throws \Doctrine\DBAL\Exception
     */
    public function boot(): void
    {
        if (!Type::hasType(DoctrineEncryptedObject::TYPE_NAME)) {
            Type::addType(DoctrineEncryptedObject::TYPE_NAME, DoctrineEncryptedObject::class);
        }

        $storage = $this->container->get(EncryptionProviderStorage::class);
        if (!$storage instanceof EncryptionProviderStorage) {
            throw new RuntimeException('Could not find ConfigManager service');
        }
        $storage->injectIntoType();
    }
}
