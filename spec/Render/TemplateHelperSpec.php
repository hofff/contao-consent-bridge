<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\Render;

use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\Exception\RuntimeException;
use Hofff\Contao\Consent\Bridge\Render\TemplateHelper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class TemplateHelperSpec extends ObjectBehavior
{
    public function let(ConsentToolManager $consentToolManager) : void
    {
        $this->beConstructedWith($consentToolManager);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(TemplateHelper::class);
    }

    public function it_renders_content_block(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        ConsentId $consentId
    ) : void {
        $consentToolManager->activeConsentTool()
            ->shouldBeCalled()
            ->willReturn($consentTool);

        $consentTool->requiresConsent($consentId)
            ->shouldBeCalled()
            ->willReturn(true);

        $consentTool->renderRaw(Argument::type('string'), $consentId, null)
            ->shouldBeCalled()
            ->willReturn('');

        $this->contentBlock($consentId);
        $this->endBlock();
    }

    public function it_renders_placeholder_block(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        ConsentId $consentId
    ) : void {
        $consentToolManager->activeConsentTool()
            ->shouldBeCalled()
            ->willReturn($consentTool);

        $consentTool->requiresConsent($consentId)
            ->shouldBeCalled()
            ->willReturn(true);

        $consentTool->renderPlaceholder(Argument::type('string'), $consentId)
            ->shouldBeCalled()
            ->willReturn('');

        $this->placeholderBlock($consentId);
        $this->endBlock();
    }

    public function it_does_not_buffer_for_non_active_consent_tool(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        ConsentId $consentId
    ) : void {
        $consentToolManager->activeConsentTool()
            ->shouldBeCalled()
            ->willReturn(null);

        $consentTool->requiresConsent($consentId)
            ->shouldNotBeCalled();

        $consentTool->renderRaw(Argument::type('string'), $consentId)
            ->shouldNotBeCalled();

        $this->contentBlock($consentId);
    }

    public function it_does_not_buffer_if_consent_not_required(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        ConsentId $consentId
    ) : void {
        $consentToolManager->activeConsentTool()
            ->shouldBeCalled()
            ->willReturn($consentTool);

        $consentTool->requiresConsent($consentId)
            ->shouldBeCalled()
            ->willReturn(false);

        $consentTool->renderRaw(Argument::type('string'), $consentId)
            ->shouldNotBeCalled();

        $this->contentBlock($consentId);
    }

    public function it_does_not_allow_start_multiple_blocks(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        ConsentId $consentId
    ) : void {
        $consentToolManager->activeConsentTool()
            ->shouldBeCalled()
            ->willReturn($consentTool);

        $consentTool->requiresConsent($consentId)
            ->shouldBeCalled()
            ->willReturn(true);

        $consentTool->renderPlaceholder(Argument::type('string'), $consentId)
            ->shouldNotBeCalled()
            ->willReturn('');

        $this->placeholderBlock($consentId);
        $this->shouldThrow(RuntimeException::class)->during('placeholderBlock', [$consentId]);
        $this->shouldThrow(RuntimeException::class)->during('contentBlock', [$consentId]);
    }
}
