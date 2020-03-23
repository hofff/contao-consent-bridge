<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\FrontendTemplate;
use Contao\Template;
use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\EventListener\Hook\ParseFrontendTemplateListener;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use function expect;

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

    public function it_injects_active_consent_tool_in_frontend_template(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        FrontendTemplate $template
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentToolManager->activeConsentTool()->willReturn($consentTool);

        $this->onParseTemplate($template);

        expect($template->activeConsentTool)->shouldReturn($consentTool);
    }

    public function it_does_not_inject_active_consent_tool_for_non_frontend_templates(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        Template $template
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentToolManager->activeConsentTool()->willReturn($consentTool);

        $this->onParseTemplate($template);

        expect($template->activeConsentTool)->shouldReturn(null);
    }

    public function it_does_not_inject_active_consent_tool_in_non_frontend_request(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        FrontendTemplate $template
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(false);

        $consentToolManager->activeConsentTool()->willReturn($consentTool);

        $this->onParseTemplate($template);

        expect($template->activeConsentTool)->shouldReturn(null);
    }

    public function it_does_not_inject_active_consent_tool_if_active_consent_tool_key_exists(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        FrontendTemplate $template
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentToolManager->activeConsentTool()->willReturn($consentTool);

        $template->activeConsentTool = 'foo';

        $this->onParseTemplate($template);

        expect($template->activeConsentTool)->shouldReturn('foo');
    }

    public function it_does_not_inject_consent_tool_if_non_given(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        Template $template
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentToolManager->activeConsentTool()->willReturn(null);

        $this->onParseTemplate($template);

        expect($template->activeConsentTool)->shouldReturn(null);
    }
}
