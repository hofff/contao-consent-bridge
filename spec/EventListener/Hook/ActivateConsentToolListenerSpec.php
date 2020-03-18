<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\LayoutModel;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\EventListener\Hook\ActivateConsentToolListener;
use PhpSpec\ObjectBehavior;
use function expect;

final class ActivateConsentToolListenerSpec extends ObjectBehavior
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    public function let() : void
    {
        $this->consentToolManager = new ConsentToolManager();

        $this->beConstructedWith($this->consentToolManager);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(ActivateConsentToolListener::class);
    }

    public function it_activates_consent_tool(
        ConsentTool $consentTool,
        PageModel $pageModel,
        LayoutModel
        $layoutModel
    ) : void {
        $pageModel->getWrappedObject()->hofff_consent_bridge_consent_tool = 'example';

        $consentTool->name()->willReturn('example');
        $consentTool->activate($pageModel, $layoutModel)->shouldBeCalled();

        $this->consentToolManager->register($consentTool->getWrappedObject());

        $this->onGetPageLayout($pageModel, $layoutModel);

        expect($this->consentToolManager->activeConsentTool())->shouldReturn($consentTool);
    }

    public function it_does_not_activate_unknown_consent_tool(
        ConsentTool $consentTool,
        PageModel $pageModel,
        LayoutModel $layoutModel
    ) : void {
        $pageModel->getWrappedObject()->hofff_consent_bridge_consent_tool = 'unknown';

        $consentTool->name()->willReturn('example');
        $consentTool->activate($pageModel, $layoutModel)->shouldNotBeCalled();

        $this->consentToolManager->register($consentTool->getWrappedObject());

        $this->onGetPageLayout($pageModel, $layoutModel);

        expect($this->consentToolManager->activeConsentTool())->shouldReturn(null);
    }
}
