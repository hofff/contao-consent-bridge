<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\DependencyInjection\Compiler;

use Hofff\Contao\Consent\Bridge\Bridge;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class LoadPluginPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container) : void
    {
        if (! $container->hasDefinition(Bridge::class)) {
            return;
        }

        $definition = $container->getDefinition(Bridge::class);
        $plugins    = $this->findAndSortTaggedServices('hofff_contao_consent_bridge.plugin');

        foreach ($plugins as $plugin) {
            $definition->addMethodCall('load', [$plugin]);
        }
    }
}
