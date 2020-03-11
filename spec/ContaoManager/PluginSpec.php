<?php

namespace spec\Hofff\Contao\Consent\Bridge\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Hofff\Contao\Consent\Bridge\ContaoManager\Plugin;
use Hofff\Contao\Consent\Bridge\HofffContaoConsentBridgeBundle;
use PhpSpec\ObjectBehavior;

final class PluginSpec extends ObjectBehavior
{
    public function it_is_initializable() : void
    {
        $this->shouldHaveType(Plugin::class);
    }

    public function it_is_a_bundle_plugin() : void
    {
        $this->shouldImplement(BundlePluginInterface::class);
    }

    public function it_registers_bridge_bundle(ParserInterface $parser) : void
    {
        $this->getBundles($parser)->shouldHaveCount(1);

        $this->getBundles($parser)[0]->shouldImplement(ConfigInterface::class);
        $this->getBundles($parser)[0]->getName()->shouldReturn(HofffContaoConsentBridgeBundle::class);
        $this->getBundles($parser)[0]->getLoadAfter()->shouldReturn([ContaoCoreBundle::class]);
    }
}
