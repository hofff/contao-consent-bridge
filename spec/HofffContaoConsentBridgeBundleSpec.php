<?php

namespace spec\Hofff\Contao\Consent\Bridge;

use Hofff\Contao\Consent\Bridge\HofffContaoConsentBridgeBundle;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

final class HofffContaoConsentBridgeBundleSpec extends ObjectBehavior
{
    public function it_is_initializable() : void
    {
        $this->shouldHaveType(HofffContaoConsentBridgeBundle::class);
    }

    public function it_is_a_kernel_bundle() : void
    {
        $this->shouldImplement(BundleInterface::class);
        $this->shouldBeAnInstanceOf(Bundle::class);
    }
}
