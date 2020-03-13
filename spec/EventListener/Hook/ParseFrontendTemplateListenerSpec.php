<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\EventListener\Hook\ParseFrontendTemplateListener;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class ParseFrontendTemplateListenerSpec extends ObjectBehavior
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    public function let(RequestScopeMatcher $scopeMatcher) : void
    {
        $this->consentToolManager = new ConsentToolManager();

        $this->beConstructedWith($this->consentToolManager, $scopeMatcher);

    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(ParseFrontendTemplateListener::class);
    }

    public function it_renders_supported_template(
        ConsentTool $consentTool,
        ConsentId $consentId,
        RequestScopeMatcher $scopeMatcher
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentTool->determineConsentIdByName('template_name')
            ->willReturn($consentId);

        $consentTool->renderHtml(Argument::type('string'), $consentId)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $this->consentToolManager->activate($consentTool->getWrappedObject());

        $this->onParseFrontendTemplate('<html></html>', 'template_name')->shouldReturn('wrapped');
    }

    public function it_bypass_unsupported_template(
        ConsentTool $consentTool,
        ConsentId $consentId,
        RequestScopeMatcher $scopeMatcher
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentTool->determineConsentIdByName('template_name')
            ->willReturn(null);

        $consentTool->renderHtml(Argument::type('string'), $consentId)
            ->shouldNotBeCalled()
            ->willReturn('<html></html>');

        $this->consentToolManager->activate($consentTool->getWrappedObject());

        $this->onParseFrontendTemplate('<html></html>', 'template_name')->shouldReturn('<html></html>');
    }
}
