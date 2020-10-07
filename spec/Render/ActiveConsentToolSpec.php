<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\Render;

use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\Render\ActiveConsentTool;
use Netzmacht\Html\Attributes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class ActiveConsentToolSpec extends ObjectBehavior
{
    public function let(ConsentTool $consentTool, ConsentIdParser $parser) : void
    {
        $this->beConstructedWith($consentTool, $parser);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(ActiveConsentTool::class);
    }

    public function it_gets_name_from_consent_tool(ConsentTool $consentTool) : void
    {
        $consentTool->name()->shouldBeCalled()->willReturn('foo');
        $this->name()->shouldReturn('foo');
    }

    public function it_delegates_activate_to_consent_tool(ConsentTool $consentTool, PageModel $rootPageModel) : void
    {
        $consentTool->activate(Argument::cetera())->willReturn(true);
        $consentTool->activate($rootPageModel, null, null)->shouldBeCalled();

        $this->activate($rootPageModel);
    }

    public function it_delegates_consent_id_options_to_consent_tool(ConsentTool $consentTool) : void
    {
        $consentTool->consentIdOptions(null)->shouldBeCalled()->willReturn([]);
        $this->consentIdOptions();
    }

    public function it_delegates_requires_consent_to_consent_tool(ConsentTool $consentTool, ConsentId $consentId) : void
    {
        $consentTool->requiresConsent($consentId)->shouldBeCalled()->willReturn(true);
        $this->requiresConsent($consentId)->shouldReturn(true);
    }

    public function it_determine_consent_id_from_template_through_consent_tool(
        ConsentTool $consentTool,
        ConsentId $consentId
    ) : void {
        $consentTool->determineConsentIdByName(Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($consentId);

        $this->determineConsentIdByName('foo_bar')->shouldReturn($consentId);
    }

    public function it_delegates_render_content_to_consent_tool(ConsentTool $consentTool, ConsentId $consentId) : void
    {
        $consentTool->renderContent('foo', $consentId, null, null)->shouldBeCalled()->willReturn('bar');

        $this->renderContent('foo', $consentId)->shouldReturn('bar');
    }

    public function it_delegates_render_raw_to_consent_tool(ConsentTool $consentTool, ConsentId $consentId) : void
    {
        $consentTool->renderRaw('foo', $consentId, null)->shouldBeCalled()->willReturn('bar');

        $this->renderRaw('foo', $consentId)->shouldReturn('bar');
    }

    public function it_delegates_render_placeholder_to_consent_tool(
        ConsentTool $consentTool,
        ConsentId $consentId
    ) : void {
        $consentTool->renderPlaceholder('foo', $consentId)->shouldBeCalled()->willReturn('bar');

        $this->renderPlaceholder('foo', $consentId)->shouldReturn('bar');
    }

    public function it_delegates_render_script_to_consent_tool(
        ConsentTool $consentTool,
        ConsentId $consentId,
        Attributes $attributes
    ) : void {
        $consentTool->renderScript($attributes, $consentId)->shouldBeCalled()->willReturn('bar');

        $this->renderScript($attributes, $consentId)->shouldReturn('bar');
    }

    public function it_delegates_render_style_to_consent_tool(
        ConsentTool $consentTool,
        ConsentId $consentId,
        Attributes $attributes
    ) : void {
        $consentTool->renderStyle($attributes, $consentId)->shouldBeCalled()->willReturn('bar');

        $this->renderStyle($attributes, $consentId)->shouldReturn('bar');
    }

    public function it_delegates_parse_consent_id_to_consent_id_parser(
        ConsentIdParser $parser,
        ConsentId $consentId
    ) : void {
        $parser->parse('foo:bar')->shouldBeCalled()->willReturn($consentId);

        $this->parseConsentId('foo:bar')->shouldReturn($consentId);
    }
}
