<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\ConsentId;

use Hofff\Contao\Consent\Bridge\ConsentId\AnalyticsConsentId;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentId\WebfontsConsentId;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

final class ConsentIdParserSpec extends ObjectBehavior
{
    public function let() : void
    {
        $this->beConstructedWith([AnalyticsConsentId::class, WebfontsConsentId::class]);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(ConsentIdParser::class);
    }

    public function it_create_supported_consent_ids() : void
    {
        $this->parse('analytics:google')->shouldBeAnInstanceOf(AnalyticsConsentId::class);
        $this->parse('webfonts:google')->shouldBeAnInstanceOf(WebfontsConsentId::class);
    }

    public function it_throws_on_unsupported_consent_id(): void
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('parse', ['test:foo']);
    }
}
