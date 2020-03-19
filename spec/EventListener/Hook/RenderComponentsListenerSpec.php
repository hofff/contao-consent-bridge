<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ContentModel;
use Contao\ModuleModel;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\EventListener\Hook\RenderComponentsListener;
use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;
use Hofff\Contao\Consent\Bridge\Plugin\BasePlugin;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class RenderComponentsListenerSpec extends ObjectBehavior
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    /** @var Bridge */
    private $bridge;

    /** @var ConsentId\ConsentIdParser */
    private $consentIdParser;

    public function let(RequestScopeMatcher $scopeMatcher, ConsentToolManager $consentToolManager, ConsentTool $consentTool) : void
    {
        $this->bridge          = new Bridge();
        $this->consentIdParser = new ConsentId\ConsentIdParser($this->bridge);

        $consentToolManager->activeConsentTool()->willReturn($consentTool);

        $this->beConstructedWith($consentToolManager, $scopeMatcher, $this->consentIdParser, $this->bridge);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(RenderComponentsListener::class);
    }

    public function it_renders_supported_content_element(
        ConsentToolManager $consentToolManager,
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        ContentModel $model,
        PageModel $pageModel
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $model->getWrappedObject()->type                     = 'foo';
        $model->getWrappedObject()->hofff_consent_bridge_tag = 'consent_id';

        $consentTool->renderContent(Argument::type('string'), Argument::type(ConsentId::class), $model)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $this->bridge->load(
            new class extends BasePlugin {
                public function providedConsentIds() : array
                {
                    return [ConsentIdMock::class];
                }

                public function supportedContentElements() : array
                {
                    return ['foo'];
                }
            }
        );

        $this->onGetContentElement($model, '<html></html>')->shouldReturn('wrapped');
    }

    public function it_bypass_unsupported_content_element(
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        ContentModel $model,
        PageModel $pageModel
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $model->getWrappedObject()->type                     = 'foo';
        $model->getWrappedObject()->hofff_consent_bridge_tag = 'consent_id';

        $consentTool->renderContent(Argument::type('string'), Argument::type(ConsentId::class), $model)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $this->bridge->load(
            new class extends BasePlugin {
                public function providedConsentIds() : array
                {
                    return [ConsentIdMock::class];
                }

                public function supportedContentElements() : array
                {
                    return ['foo'];
                }
            }
        );

        $this->onGetContentElement($model, '<html></html>')->shouldReturn('wrapped');
    }

    public function it_renders_supported_frontend_module(
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        PageModel $pageModel,
        ModuleModel $model
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $model->getWrappedObject()->type                     = 'foo';
        $model->getWrappedObject()->hofff_consent_bridge_tag = 'consent_id';

        $consentTool->renderContent(Argument::type('string'), Argument::type(ConsentId::class), $model)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $this->bridge->load(
            new class extends BasePlugin {
                public function providedConsentIds() : array
                {
                    return [ConsentIdMock::class];
                }

                public function supportedFrontendModules() : array
                {
                    return ['foo'];
                }
            }
        );

        $this->onGetFrontendModule($model, '<html></html>')->shouldReturn('wrapped');
    }

    public function it_bypass_unsupported_frontend_module(
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        PageModel $pageModel,
        ModuleModel $model
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $model->getWrappedObject()->type                     = 'foo';
        $model->getWrappedObject()->hofff_consent_bridge_tag = 'consent_id';

        $consentTool->renderContent(Argument::type('string'), Argument::type(ConsentId::class), $model)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $this->bridge->load(
            new class extends BasePlugin {
                public function providedConsentIds() : array
                {
                    return [ConsentIdMock::class];
                }

                public function supportedFrontendModules() : array
                {
                    return ['foo'];
                }
            }
        );

        $this->onGetFrontendModule($model, '<html></html>')->shouldReturn('wrapped');
    }
}

final class ConsentIdMock implements ConsentId
{
    public static function supports(string $string) : bool
    {
        return $string === 'consent_id';
    }

    public static function fromString(string $string) : ConsentId
    {
        return new self();
    }

    public function equals(ConsentId $other) : bool
    {
        return $other instanceof self;
    }

    public function toString() : string
    {
        return 'consent_id';
    }

    public function __toString() : string
    {
        return $this->toString();
    }
}
