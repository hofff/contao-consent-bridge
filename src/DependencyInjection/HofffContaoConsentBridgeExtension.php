<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\DependencyInjection;

use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\Plugin;
use Override;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class HofffContaoConsentBridgeExtension extends Extension
{
    /** @param mixed[] $configs */
    #[Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config'),
        );

        $loader->load('services.yaml');
        $loader->load('listener.yaml');

        /** @psalm-var array{content_elements:array<string,string>, frontend_modules:array<string,string>} $config */
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('hofff_contao_consent_bridge.content_elements', $config['content_elements']);
        $container->setParameter('hofff_contao_consent_bridge.frontend_modules', $config['frontend_modules']);

        $container
            ->registerForAutoconfiguration(Plugin::class)
            ->addTag('hofff_contao_consent_bridge.plugin');
        $container
            ->registerForAutoconfiguration(ConsentTool::class)
            ->addTag('hofff_contao_consent_bridge.consent_tool');
    }
}
