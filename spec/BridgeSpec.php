<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge;

use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentId\AnalyticsConsentId;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\Exception\UnsupportedContentElement;
use Hofff\Contao\Consent\Bridge\Exception\UnsupportedFrontendModule;
use Hofff\Contao\Consent\Bridge\Plugin;
use Hofff\Contao\Consent\Bridge\Render\RenderInformation;
use PhpSpec\ObjectBehavior;

final class BridgeSpec extends ObjectBehavior
{
    public function let(Plugin $pluginA, Plugin $pluginB): void
    {
        $this->beConstructedWith([$pluginA, $pluginB]);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Bridge::class);
    }

    public function it_loads_plugins(Plugin $pluginA, Plugin $pluginB): void
    {
        $pluginA->load($this)->shouldBeCalled();
        $pluginB->load($this)->shouldBeCalled();

        $this->getWrappedObject()->__construct(
            [$pluginA->getWrappedObject(), $pluginB->getWrappedObject()],
        );
    }

    public function it_registers_consent_tools(ConsentTool $consentToolA, ConsentTool $consentToolB): void
    {
        $consentToolA->name()->willReturn('a');
        $consentToolB->name()->willReturn('b');

        $this->registerConsentTool($consentToolA)->shouldReturn($this);
        $this->registerConsentTool($consentToolB)->shouldReturn($this);

        $this->consentTools()->shouldReturn(
            [
                'a' => $consentToolA,
                'b' => $consentToolB,
            ],
        );
    }

    public function it_registers_consent_ids(): void
    {
        $this->registerConsentId(ConsentId::class, AnalyticsConsentId::class);
        $this->providedConsentIds()->shouldReturn([ConsentId::class, AnalyticsConsentId::class]);
    }

    public function it_registers_content_elements(): void
    {
        $renderInformationA = RenderInformation::autoRenderWithoutPlaceholder();
        $renderInformationB = RenderInformation::customRender();

        $this->supportContentElement('a', $renderInformationA);
        $this->supportContentElement('b', $renderInformationB);

        $this->supportsContentElement('a')->shouldReturn(true);
        $this->supportsContentElement('b')->shouldReturn(true);
        $this->supportsContentElement('c')->shouldReturn(false);

        $this->supportedContentElements()->shouldReturn(['a', 'b']);
        $this->contentElementRenderInformation('a')->shouldReturn($renderInformationA);
        $this->contentElementRenderInformation('b')->shouldReturn($renderInformationB);

        $this->shouldThrow(UnsupportedContentElement::class)->during('contentElementRenderInformation', ['c']);
    }

    public function it_registers_frontend_modules(): void
    {
        $renderInformationA = RenderInformation::autoRenderWithoutPlaceholder();
        $renderInformationB = RenderInformation::customRender();

        $this->supportFrontendModule('a', $renderInformationA);
        $this->supportFrontendModule('b', $renderInformationB);

        $this->supportsFrontendModule('a')->shouldReturn(true);
        $this->supportsFrontendModule('b')->shouldReturn(true);
        $this->supportsFrontendModule('c')->shouldReturn(false);

        $this->supportedFrontendModules()->shouldReturn(['a', 'b']);
        $this->frontendModuleRenderInformation('a')->shouldReturn($renderInformationA);
        $this->frontendModuleRenderInformation('b')->shouldReturn($renderInformationB);

        $this->shouldThrow(UnsupportedFrontendModule::class)->during('frontendModuleRenderInformation', ['c']);
    }

    public function it_supports_multiple_render_information(): void
    {
        $this->supportContentElement('a', RenderInformation::autoRenderWithPlaceholder('tpl_a'));
        $this->supportContentElement('a', RenderInformation::autoRenderWithPlaceholder('tpl_b'));

        $this->contentElementRenderInformation('a')->placeholderTemplate()->shouldReturn('tpl_a');
        $this->contentElementRenderInformation('a', 'tpl_b')->placeholderTemplate()->shouldReturn('tpl_b');

        $this->supportFrontendModule('a', RenderInformation::autoRenderWithPlaceholder('tpl_a'));
        $this->supportFrontendModule('a', RenderInformation::autoRenderWithPlaceholder('tpl_b'));

        $this->frontendModuleRenderInformation('a')->placeholderTemplate()->shouldReturn('tpl_a');
        $this->frontendModuleRenderInformation('a', 'tpl_b')->placeholderTemplate()->shouldReturn('tpl_b');
    }
}
