<?php

namespace Jfnetwork\DoctrineEncryptedObject;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineEncryptedObjectBundle extends Bundle
{
    public function boot()
    {
        if (!Type::hasType(DoctrineEncryptedObject::TYPE_NAME)) {
            Type::addType(DoctrineEncryptedObject::TYPE_NAME, DoctrineEncryptedObject::class);
        }

        $this->container->get(KeyManager::class)->setKey();
    }
}
