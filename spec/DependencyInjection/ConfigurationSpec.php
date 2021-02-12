<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\DependencyInjection;

use Hofff\Contao\Consent\Bridge\DependencyInjection\Configuration;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class ConfigurationSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Configuration::class);
    }

    public function it_creates_treebuilder(): void
    {
        $this->getConfigTreeBuilder()->shouldBeAnInstanceOf(TreeBuilder::class);
    }
}
