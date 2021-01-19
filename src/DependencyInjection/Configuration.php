<?php

namespace Jfnetwork\DoctrineEncryptedObject\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('doctrine_encrypted_object');
        $rootNode = $treeBuilder->getRootNode();

        $children = $rootNode->children();
        $keyNode = $children->scalarNode('key');
        $keyNode->isRequired();

        return $treeBuilder;
    }
}
