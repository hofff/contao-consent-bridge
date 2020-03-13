<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder() : TreeBuilder
    {
        $builder = new TreeBuilder();
        $rootNode = $builder->root('hofff_contao_consent_bridge');

        $rootNode
            ->addDefaultChildrenIfNoneSet()
            ->children()
                ->arrayNode('content_elements')
                    ->scalarPrototype()
                    ->end()
                ->end()
                ->arrayNode('frontend_modules')
                    ->scalarPrototype()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}