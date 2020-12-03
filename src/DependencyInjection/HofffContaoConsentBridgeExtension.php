<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\DependencyInjection;

use Hofff\Contao\Consent\Bridge\Plugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class HofffContaoConsentBridgeExtension extends Extension
{
    /**
     * @param mixed[] $configs
     */
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.xml');
        $loader->load('listener.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('hofff_contao_consent_bridge.content_elements', $config['content_elements']);
        $container->setParameter('hofff_contao_consent_bridge.frontend_modules', $config['frontend_modules']);
        
        $container
            ->registerForAutoconfiguration(Plugin::class)
            ->addTag('hofff_contao_consent_bridge.plugin')
        ;
    }
}
