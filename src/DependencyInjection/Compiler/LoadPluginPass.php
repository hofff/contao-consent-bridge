<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\DependencyInjection\Compiler;

use Hofff\Contao\Consent\Bridge\Bridge;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class LoadPluginPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container) : void
    {
        if (! $container->hasDefinition(Bridge::class)) {
            return;
        }

        $reference = new Reference(Bridge::class);
        $plugins   = $this->findAndSortTaggedServices('hofff_contao_consent_bridge.plugin', $container);

        foreach ($plugins as $plugin) {
            $definition = $container->getDefinition((string) $plugin);
            $definition->addMethodCall('load', [$reference]);
        }
    }
}
