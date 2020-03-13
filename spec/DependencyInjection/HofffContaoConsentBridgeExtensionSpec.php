<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\DependencyInjection;

use Hofff\Contao\Consent\Bridge\DependencyInjection\HofffContaoConsentBridgeExtension;
use PhpSpec\ObjectBehavior;

final class HofffContaoConsentBridgeExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable() : void
    {
        $this->shouldHaveType(HofffContaoConsentBridgeExtension::class);
    }
}
