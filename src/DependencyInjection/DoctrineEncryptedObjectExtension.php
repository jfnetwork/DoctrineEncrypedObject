<?php

namespace Jfnetwork\DoctrineEncryptedObject\DependencyInjection;

use Jfnetwork\DoctrineEncryptedObject\KeyManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DoctrineEncryptedObjectExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->register(KeyManager::class, KeyManager::class);
        $definition->setArguments([$config['key']]);
        $definition->setPublic(true);
    }
}
