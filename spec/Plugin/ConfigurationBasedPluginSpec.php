<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\Plugin;

use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\Plugin\ConfigurationBasedPlugin;
use PhpSpec\ObjectBehavior;
use function expect;

final class ConfigurationBasedPluginSpec extends ObjectBehavior
{
    private const CONFIG = [
        [
            'type'                => 'rsce_custom',
            'mode'                => 'auto',
            'placeholderTemplate' => 'rsce_custom_placeholder',
        ],
        [
            'type' => 'fancy_slider',
            'mode' => 'auto',
        ],
        [
            'type' => 'my_element',
            'mode' => 'custom',
        ],
    ];

    public function let() : void
    {
        $this->beConstructedWith(self::CONFIG, self::CONFIG);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(ConfigurationBasedPlugin::class);
    }

    public function it_registers_content_elements() : void
    {
        $bridge = new Bridge([$this->getWrappedObject()]);

        $renderInformation = $bridge->contentElementRenderInformation('rsce_custom');
        expect($bridge->supportsContentElement('rsce_custom'))->shouldReturn(true);
        expect($renderInformation->isAutoRenderMode())->shouldReturn(true);
        expect($renderInformation->isCustomRenderMode())->shouldReturn(false);
        expect($renderInformation->placeholderTemplate())->shouldReturn('rsce_custom_placeholder');

        $renderInformation = $bridge->contentElementRenderInformation('fancy_slider');
        expect($bridge->supportsContentElement('fancy_slider'))->shouldReturn(true);
        expect($renderInformation->isAutoRenderMode())->shouldReturn(true);
        expect($renderInformation->isCustomRenderMode())->shouldReturn(false);
        expect($renderInformation->placeholderTemplate())->shouldReturn(null);

        $renderInformation = $bridge->contentElementRenderInformation('my_element');
        expect($bridge->supportsContentElement('my_element'))->shouldReturn(true);
        expect($renderInformation->isAutoRenderMode())->shouldReturn(false);
        expect($renderInformation->isCustomRenderMode())->shouldReturn(true);
        expect($renderInformation->placeholderTemplate())->shouldReturn(null);
    }

    public function it_registers_frontend_modules() : void
    {
        $bridge = new Bridge([$this->getWrappedObject()]);

        $renderInformation = $bridge->frontendModuleRenderInformation('rsce_custom');
        expect($bridge->supportsFrontendModule('rsce_custom'))->shouldReturn(true);
        expect($renderInformation->isAutoRenderMode())->shouldReturn(true);
        expect($renderInformation->isCustomRenderMode())->shouldReturn(false);
        expect($renderInformation->placeholderTemplate())->shouldReturn('rsce_custom_placeholder');

        $renderInformation = $bridge->frontendModuleRenderInformation('fancy_slider');
        expect($bridge->supportsFrontendModule('fancy_slider'))->shouldReturn(true);
        expect($renderInformation->isAutoRenderMode())->shouldReturn(true);
        expect($renderInformation->isCustomRenderMode())->shouldReturn(false);
        expect($renderInformation->placeholderTemplate())->shouldReturn(null);

        $renderInformation = $bridge->frontendModuleRenderInformation('my_element');
        expect($bridge->supportsFrontendModule('my_element'))->shouldReturn(true);
        expect($renderInformation->isAutoRenderMode())->shouldReturn(false);
        expect($renderInformation->isCustomRenderMode())->shouldReturn(true);
        expect($renderInformation->placeholderTemplate())->shouldReturn(null);
    }
}
