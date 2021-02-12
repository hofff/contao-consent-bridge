<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress PossiblyUndefinedMethod
     * @psalm-suppress DeprecatedMethod
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('hofff_contao_consent_bridge');
        $rootNode
            ->children()
                ->arrayNode('content_elements')
                    ->arrayPrototype()
                        ->children()
                            ->enumNode('mode')
                                ->values(['auto', 'custom'])
                                ->defaultValue('auto')
                            ->end()
                            ->scalarNode('placeholderTemplate')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('frontend_modules')
                    ->arrayPrototype()
                        ->children()
                            ->enumNode('mode')
                                ->values(['auto', 'custom'])
                                ->defaultValue('auto')
                            ->end()
                            ->scalarNode('placeholderTemplate')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
