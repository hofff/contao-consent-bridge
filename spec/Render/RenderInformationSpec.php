<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\Render;

use Hofff\Contao\Consent\Bridge\Render\RenderInformation;
use PhpSpec\ObjectBehavior;

final class RenderInformationSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RenderInformation::class);
    }

    public function it_describes_custom_render(): void
    {
        $this->beConstructedThrough('customRender');

        $this->isAutoRenderMode()->shouldReturn(false);
        $this->isCustomRenderMode()->shouldReturn(true);
        $this->placeholderTemplate()->shouldReturn(null);
        $this->supportsPlaceholder()->shouldReturn(false);
    }

    public function it_describes_auto_render_without_placeholder(): void
    {
        $this->beConstructedThrough('autoRenderWithoutPlaceholder');

        $this->isAutoRenderMode()->shouldReturn(true);
        $this->isCustomRenderMode()->shouldReturn(false);
        $this->placeholderTemplate()->shouldReturn(null);
        $this->supportsPlaceholder()->shouldReturn(false);
    }

    public function it_describes_auto_render_with_placeholder(): void
    {
        $this->beConstructedThrough('autoRenderWithPlaceholder', ['custom_tpl']);

        $this->isAutoRenderMode()->shouldReturn(true);
        $this->isCustomRenderMode()->shouldReturn(false);
        $this->placeholderTemplate()->shouldReturn('custom_tpl');
        $this->supportsPlaceholder()->shouldReturn(true);
    }
}
