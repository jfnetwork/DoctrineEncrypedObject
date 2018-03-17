<?php

namespace Jfnetwork\DoctrineEncryptedObject\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('doctrine_encrypted_object');

        $rootNode
            ->children()
                ->scalarNode('key')->isRequired()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
