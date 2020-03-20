<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\Exception;

use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;
use Hofff\Contao\Consent\Bridge\Exception\UnsupportedFrontendModule;
use PhpSpec\ObjectBehavior;

final class UnsupportedFrontendModuleSpec extends ObjectBehavior
{
    public function let() : void
    {
        $this->beConstructedThrough('ofType', ['foo']);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(UnsupportedFrontendModule::class);
    }

    public function it_is_an_invalid_exception() : void
    {
        $this->shouldImplement(InvalidArgumentException::class);
    }

    public function it_constructs_through_of_type() : void
    {
        $this->type()->shouldReturn('foo');
        $this->getMessage()->shouldMatch('/"foo"/');
    }
}
