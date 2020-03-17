<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\LayoutModel;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Netzmacht\Contao\Toolkit\Data\Model\RepositoryManager;

final class ActivateConsentToolListener
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    /** @var RepositoryManager */
    private $repositoryManager;

    public function __construct(ConsentToolManager $consentToolManager, RepositoryManager $repositoryManager)
    {
        $this->consentToolManager = $consentToolManager;
        $this->repositoryManager = $repositoryManager;
    }

    public function onGetPageLayout(PageModel $pageModel, LayoutModel $layoutModel) : void
    {
        $repository    = $this->repositoryManager->getRepository(PageModel::class);
        $rootPageModel = $repository->find((int) $pageModel->rootId);

        if (! $rootPageModel instanceof PageModel) {
            return;
        }

        $name = (string) $rootPageModel->hofff_consent_bridge_consent_tool;
        if (! $this->consentToolManager->has($name)) {
            return;
        }

        $this->consentToolManager->activate($name, $rootPageModel, $pageModel, $layoutModel);
    }
}
