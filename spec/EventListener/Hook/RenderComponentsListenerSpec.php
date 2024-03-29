<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ContentModel;
use Contao\Model;
use Contao\ModuleModel;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\EventListener\Hook\RenderComponentsListener;
use Hofff\Contao\Consent\Bridge\Render\RenderInformation;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use ReflectionClass;

final class RenderComponentsListenerSpec extends ObjectBehavior
{
    private Bridge $bridge;

    public function let(
        RequestScopeMatcher $scopeMatcher,
        ConsentToolManager $consentToolManager,
        ConsentIdParser $consentIdParser,
        ConsentTool $consentTool,
    ): void {
        $this->bridge = new Bridge([]);

        $consentToolManager->activeConsentTool()->willReturn($consentTool);

        $this->beConstructedWith($consentToolManager, $scopeMatcher, $consentIdParser, $this->bridge);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RenderComponentsListener::class);
    }

    public function it_renders_supported_content_element(
        ConsentIdParser $consentIdParser,
        ConsentTool $consentTool,
        ConsentId $consentId,
        RequestScopeMatcher $scopeMatcher,
    ): void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $modelReflection = (new ReflectionClass(Model::class));
        if ($modelReflection->hasProperty('arrColumnCastTypes')) {
            $modelReflection->getProperty('arrColumnCastTypes')->setValue(['arrColumnCastTypes' => []]);
        }

        $model = (new ReflectionClass(ContentModel::class))->newInstanceWithoutConstructor();

        $model->type                     = 'foo';
        $model->hofff_consent_bridge_tag = 'consent_id';

        $consentTool->renderContent(Argument::type('string'), Argument::type(ConsentId::class), $model, null)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $consentTool->requiresConsent($consentId)->willReturn(true);

        $consentIdParser->parse(Argument::type('string'))->willReturn($consentId);

        $this->bridge->supportContentElement('foo', RenderInformation::autoRenderWithoutPlaceholder());

        $this->onGetContentElement($model, '<html></html>')->shouldReturn('wrapped');
    }

    public function it_bypass_unsupported_content_element(
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        ConsentIdParser $consentIdParser,
        ConsentId $consentId,
    ): void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $modelReflection = (new ReflectionClass(Model::class));
        if ($modelReflection->hasProperty('arrColumnCastTypes')) {
            $modelReflection->getProperty('arrColumnCastTypes')->setValue(['arrColumnCastTypes' => []]);
        }

        $model = (new ReflectionClass(ContentModel::class))->newInstanceWithoutConstructor();

        $model->type                     = 'foo';
        $model->hofff_consent_bridge_tag = 'consent_id';

        $consentIdParser->parse(Argument::type('string'))->willReturn($consentId);

        $consentTool->renderContent(Argument::type('string'), Argument::type(ConsentId::class), $model, null)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $consentTool->requiresConsent($consentId)->willReturn(true);

        $this->bridge->supportContentElement('foo', RenderInformation::autoRenderWithoutPlaceholder());

        $this->onGetContentElement($model, '<html></html>')->shouldReturn('wrapped');
    }

    public function it_renders_supported_frontend_module(
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        ConsentIdParser $consentIdParser,
        ConsentId $consentId,
    ): void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $modelReflection = (new ReflectionClass(Model::class));
        if ($modelReflection->hasProperty('arrColumnCastTypes')) {
            $modelReflection->getProperty('arrColumnCastTypes')->setValue(['arrColumnCastTypes' => []]);
        }

        $model = (new ReflectionClass(ModuleModel::class))->newInstanceWithoutConstructor();

        $model->type                     = 'foo';
        $model->hofff_consent_bridge_tag = 'consent_id';

        $consentIdParser->parse(Argument::type('string'))->willReturn($consentId);

        $consentTool->renderContent(Argument::type('string'), Argument::type(ConsentId::class), $model, null)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $consentTool->requiresConsent($consentId)->willReturn(true);

        $this->bridge->supportFrontendModule('foo', RenderInformation::autoRenderWithoutPlaceholder());

        $this->onGetFrontendModule($model, '<html></html>')->shouldReturn('wrapped');
    }

    public function it_bypass_unsupported_frontend_module(
        ConsentTool $consentTool,
        RequestScopeMatcher $scopeMatcher,
        ConsentIdParser $consentIdParser,
        ConsentId $consentId,
    ): void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $modelReflection = (new ReflectionClass(Model::class));
        if ($modelReflection->hasProperty('arrColumnCastTypes')) {
            $modelReflection->getProperty('arrColumnCastTypes')->setValue(['arrColumnCastTypes' => []]);
        }

        $model = (new ReflectionClass(ModuleModel::class))->newInstanceWithoutConstructor();

        $model->type                     = 'foo';
        $model->hofff_consent_bridge_tag = 'consent_id';

        $consentIdParser->parse(Argument::type('string'))->willReturn($consentId);

        $consentTool->renderContent(Argument::type('string'), Argument::type(ConsentId::class), $model, null)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $consentTool->requiresConsent($consentId)->willReturn(true);

        $this->bridge->supportFrontendModule('foo', RenderInformation::autoRenderWithoutPlaceholder());

        $this->onGetFrontendModule($model, '<html></html>')->shouldReturn('wrapped');
    }
}
