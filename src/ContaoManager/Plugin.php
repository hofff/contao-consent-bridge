<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Hofff\Contao\Consent\Bridge\HofffContaoConsentBridgeBundle;
use MadeYourDay\RockSolidCustomElements\RockSolidCustomElementsBundle;

final class Plugin implements BundlePluginInterface
{
    /**
     * @return ConfigInterface[]
     *
     * @psalm-suppress UndefinedClass
     */
    public function getBundles(ParserInterface $parser) : array
    {
        return [
            BundleConfig::create(HofffContaoConsentBridgeBundle::class)
                ->setLoadAfter(
                    [
                        ContaoCoreBundle::class,
                        RockSolidCustomElementsBundle::class,
                    ]
                ),
        ];
    }
}
