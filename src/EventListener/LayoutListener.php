<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener;

use Contao\CoreBundle\Event\LayoutEvent;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Netzmacht\Contao\Toolkit\Data\Model\RepositoryManager;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final class LayoutListener
{
    public function __construct(
        private ConsentToolManager $consentToolManager,
        private RepositoryManager $repositoryManager,
    ) {
    }

    public function __invoke(LayoutEvent $event): void
    {
        $pageModel  = $event->getPage();
        $repository = $this->repositoryManager->getRepository(PageModel::class);
        /** @psalm-suppress RedundantCastGivenDocblockType */
        $rootPageModel = $repository->find((int) $pageModel->rootId);

        if (! $rootPageModel instanceof PageModel) {
            return;
        }

        // phpcs:disable
        $name = (string) $rootPageModel->hofff_consent_bridge_consent_tool;
        // phpcs:enable
        if (! $this->consentToolManager->has($name)) {
            return;
        }

        $this->consentToolManager->activate($name, $rootPageModel, $pageModel, $event->getLayout());
    }
}
