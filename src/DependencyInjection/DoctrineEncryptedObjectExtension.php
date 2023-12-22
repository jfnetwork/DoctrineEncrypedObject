<?php

namespace Jfnetwork\DoctrineEncryptedObject\DependencyInjection;

use Jfnetwork\DoctrineEncryptedObject\EncryptionProviderStorage;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DoctrineEncryptedObjectExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        // $configuration = new Configuration();
        // $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config'),
        );
        $loader->load('doctrine_encrypted_object.yaml');

        // $definition = $container->register(EncryptionProviderStorage::class, EncryptionProviderStorage::class);
        // $definition->setAutoconfigured(true);
        // $definition->setArguments([
        //     $config['key'],
        // ]);
        // $definition->setPublic(true);
    }
}
