<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\LayoutModel;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\EventListener\Hook\GoogleWebfontsListener;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use Netzmacht\Html\Attributes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class GoogleWebfontsListenerSpec extends ObjectBehavior
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    /** @var Bridge */
    private $bridge;

    /** @var ConsentIdParser */
    private $consentIdParser;

    public function let(RequestScopeMatcher $scopeMatcher) : void
    {
        $this->consentToolManager = new ConsentToolManager();
        $this->bridge             = new Bridge();
        $this->consentIdParser    = new ConsentIdParser($this->bridge);

        $this->beConstructedWith($this->consentToolManager, $scopeMatcher, $this->consentIdParser);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(GoogleWebfontsListener::class);
    }

    public function it_renders_supported_template(
        ConsentTool $consentTool,
        ConsentId $consentId,
        RequestScopeMatcher $scopeMatcher,
        PageModel $rootPageModel,
        PageModel $pageModel,
        LayoutModel $layoutModel
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentTool->name()->willReturn('example');

        $consentTool->determineConsentIdByName('google_webfonts')
            ->willReturn($consentId);

        $consentTool->renderStyle(Argument::type(Attributes::class), $consentId)
            ->shouldBeCalled()
            ->willReturn('wrapped');

        $consentTool->activate(
            $rootPageModel->getWrappedObject(),
            $pageModel->getWrappedObject(),
            $layoutModel->getWrappedObject()
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this->consentToolManager->register($consentTool->getWrappedObject());
        $this->consentToolManager->activate(
            'example',
            $rootPageModel->getWrappedObject(),
            $pageModel->getWrappedObject(),
            $layoutModel->getWrappedObject()
        );

        $this->onGeneratePage($pageModel, $layoutModel);
    }

    public function it_bypass_unsupported_template(
        ConsentTool $consentTool,
        ConsentId $consentId,
        RequestScopeMatcher $scopeMatcher,
        PageModel $rootPageModel,
        PageModel $pageModel,
        LayoutModel $layoutModel
    ) : void {
        $scopeMatcher->isFrontendRequest()->willReturn(true);

        $consentTool->name()->willReturn('example');

        $consentTool->determineConsentIdByName('google_webfonts')
            ->willReturn(null);

        $consentTool->renderStyle(Argument::type(Attributes::class), $consentId)
            ->shouldNotBeCalled()
            ->willReturn('wrapped');

        $consentTool->activate(
            $rootPageModel->getWrappedObject(),
            $pageModel->getWrappedObject(),
            $layoutModel->getWrappedObject()
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this->consentToolManager->register($consentTool->getWrappedObject());
        $this->consentToolManager->activate(
            'example',
            $rootPageModel->getWrappedObject(),
            $pageModel->getWrappedObject(),
            $layoutModel->getWrappedObject()
        );

        $this->onGeneratePage($pageModel, $layoutModel);
    }
}
