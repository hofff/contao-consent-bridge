<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\Bridge;
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

    /** @var Bridge */
    private $bridge;

    /** @var ConsentId\ConsentIdParser */
    private $consentIdParser;

    public function let(RequestScopeMatcher $scopeMatcher) : void
    {
        $this->consentToolManager = new ConsentToolManager();
        $this->bridge             = new Bridge();
        $this->consentIdParser    = new ConsentId\ConsentIdParser($this->bridge);

        $this->beConstructedWith($this->consentToolManager, $scopeMatcher, $this->consentIdParser);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(ParseFrontendTemplateListener::class);
    }

    public function it_renders_supported_template(
        ConsentTool $consentTool,
        ConsentId $consentId,
        PageModel $pageModel,
        RequestScopeMatcher $scopeMatcher
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentTool->name()->willReturn('example');

        $consentTool->determineConsentIdByName('template_name')
            ->willReturn($consentId);

        $consentTool->renderRaw(Argument::type('string'), $consentId)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $consentTool->activate($pageModel, null, null)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->consentToolManager->register($consentTool->getWrappedObject());
        $this->consentToolManager->activate('example', $pageModel->getWrappedObject());

        $this->onParseFrontendTemplate('<html></html>', 'template_name')->shouldReturn('wrapped');
    }

    public function it_bypass_unsupported_template(
        ConsentTool $consentTool,
        ConsentId $consentId,
        PageModel $pageModel,
        RequestScopeMatcher $scopeMatcher
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentTool->name()->willReturn('example');

        $consentTool->determineConsentIdByName('template_name')
            ->willReturn(null);

        $consentTool->renderRaw(Argument::type('string'), $consentId)
            ->shouldNotBeCalled()
            ->willReturn('wrapped');

        $consentTool->activate($pageModel, null, null)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->consentToolManager->register($consentTool->getWrappedObject());
        $this->consentToolManager->activate('example', $pageModel->getWrappedObject());

        $this->onParseFrontendTemplate('<html></html>', 'template_name')->shouldReturn('<html></html>');
    }
}
