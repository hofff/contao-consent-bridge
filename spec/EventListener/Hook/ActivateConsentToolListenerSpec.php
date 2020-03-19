<?php

declare(strict_types=1);

namespace spec\Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\LayoutModel;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\EventListener\Hook\ActivateConsentToolListener;
use Netzmacht\Contao\Toolkit\Data\Model\Repository;
use Netzmacht\Contao\Toolkit\Data\Model\RepositoryManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use function expect;

final class ActivateConsentToolListenerSpec extends ObjectBehavior
{
    public function let(RepositoryManager $repositoryManager, Repository $pageRepository, ConsentToolManager $consentToolManager) : void
    {
        $repositoryManager->getRepository(PageModel::class)
            ->willReturn($pageRepository);

        $this->beConstructedWith($consentToolManager, $repositoryManager);
    }

    public function it_is_initializable() : void
    {
        $this->shouldHaveType(ActivateConsentToolListener::class);
    }

    public function it_activates_consent_tool(
        ConsentToolManager $consentToolManager,
        Repository $pageRepository,
        PageModel $rootPageModel,
        PageModel $pageModel,
        LayoutModel $layoutModel
    ) : void {
        $pageModel->getWrappedObject()->rootId = 1;
        $rootPageModel->getWrappedObject()->hofff_consent_bridge_consent_tool = 'example';

        $pageRepository->find(1)
            ->shouldBeCalled()
            ->willReturn($rootPageModel);

        $consentToolManager->has(Argument::type('string'))->willReturn(true);
        $consentToolManager->activate('example', $rootPageModel, $pageModel, $layoutModel)
            ->willReturn(true);

        $this->onGetPageLayout($pageModel, $layoutModel);

        $consentToolManager->activeConsentTool();
    }

    public function it_does_not_activate_unknown_consent_tool(
        ConsentToolManager $consentToolManager,
        Repository $pageRepository,
        PageModel $rootPageModel,
        PageModel $pageModel,
        LayoutModel $layoutModel
    ) : void {
        $pageModel->getWrappedObject()->rootId = 1;
        $rootPageModel->getWrappedObject()->hofff_consent_bridge_consent_tool = 'example';

        $pageRepository->find(1)
            ->shouldBeCalled()
            ->willReturn($rootPageModel);

        $consentToolManager->has(Argument::type('string'))->willReturn(false);
        $consentToolManager->activate('example', $rootPageModel, $pageModel, $layoutModel)
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this->onGetPageLayout($pageModel, $layoutModel);

        $consentToolManager->activeConsentTool();
    }
}
