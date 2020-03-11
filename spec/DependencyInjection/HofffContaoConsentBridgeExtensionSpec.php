<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\DependencyInjection;

use Hofff\Contao\Consent\Bridge\DependencyInjection\HofffContaoConsentBridgeExtension;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

final class HofffContaoConsentBridgeExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable() : void
    {
        $this->shouldHaveType(HofffContaoConsentBridgeExtension::class);
    }

    public function it_loads_services(ContainerBuilder $container, ExtensionInterface $extension) : void
    {
        $extension->getXsdValidationBasePath()
            ->willReturn(false);

        $container->hasExtension('http://symfony.com/schema/dic/services')
            ->willReturn(true);

        $container->getExtension('http://symfony.com/schema/dic/services')
            ->willReturn($extension);

        $container->fileExists(Argument::type('string'))
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        $this->load([], $container);
    }
}
