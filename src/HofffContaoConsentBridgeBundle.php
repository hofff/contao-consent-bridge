<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Hofff\Contao\Consent\Bridge\DependencyInjection\Compiler\RegisterConsentToolPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class HofffContaoConsentBridgeBundle extends Bundle
{
    public function build(ContainerBuilder $container) : void
    {
        $container->addCompilerPass(new RegisterConsentToolPass());
    }
}
