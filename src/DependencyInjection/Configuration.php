<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use function method_exists;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress PossiblyUndefinedMethod
     * @psalm-suppress DeprecatedMethod
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('hofff_contao_consent_bridge');

        // BC for Symfony 3.4
        if (method_exists($treeBuilder, 'root')) {
            $rootNode = $treeBuilder->root('hofff_contao_consent_bridge');
        } else {
            $rootNode = $treeBuilder->getRootNode();
        }

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
