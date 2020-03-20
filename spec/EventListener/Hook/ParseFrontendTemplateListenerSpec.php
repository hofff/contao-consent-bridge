<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\EventListener\Hook\ParseFrontendTemplateListener;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class ParseFrontendTemplateListenerSpec extends ObjectBehavior
{
    public function let(
        RequestScopeMatcher $scopeMatcher,
        ConsentToolManager $consentToolManager,
        ConsentIdParser $consentIdParser
    ) : void {
        $this->beConstructedWith($consentToolManager, $scopeMatcher, $consentIdParser);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(ParseFrontendTemplateListener::class);
    }

    public function it_renders_supported_template(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        ConsentId $consentId,
        RequestScopeMatcher $scopeMatcher
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentTool->name()->willReturn('example');
        $consentTool->requiresConsent($consentId)->willReturn(true);

        $consentTool->determineConsentIdByName('template_name')
            ->willReturn($consentId);

        $consentTool->renderRaw(Argument::type('string'), $consentId)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $consentToolManager->activeConsentTool()->willReturn($consentTool);

        $this->onParseFrontendTemplate('<html></html>', 'template_name')
            ->shouldReturn('wrapped');
    }

    public function it_bypass_unsupported_template(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentTool->name()->willReturn('example');

        $consentTool->determineConsentIdByName('template_name')
            ->willReturn(null);

        $consentTool->renderRaw(Argument::type('string'), Argument::type(ConsentId::class))
            ->shouldNotBeCalled();

        $consentToolManager->activeConsentTool()->willReturn($consentTool);

        $this->onParseFrontendTemplate('<html></html>', 'template_name')
            ->shouldReturn('<html></html>');
    }
}
