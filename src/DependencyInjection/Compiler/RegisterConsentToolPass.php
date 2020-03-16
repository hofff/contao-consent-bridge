<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\DependencyInjection\Compiler;

use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterConsentToolPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container) : void
    {
        if (! $container->hasDefinition(ConsentToolManager::class)) {
            return;
        }

        $definition = $container->getDefinition(ConsentToolManager::class);
        $services   = $this->findAndSortTaggedServices('hofff_contao_consent_bridge.consent_tool');

        foreach ($services as $service) {
            $definition->addMethodCall('register', [$service]);
        }
    }
}
