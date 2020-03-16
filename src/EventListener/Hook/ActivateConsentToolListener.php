<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\LayoutModel;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;

final class ActivateConsentToolListener
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    public function __construct(ConsentToolManager $consentToolManager)
    {
        $this->consentToolManager = $consentToolManager;
    }

    public function onGetPageLayout(PageModel $pageModel, LayoutModel $layoutModel) : void
    {
        $name = (string) $pageModel->hofff_consent_bridge_consent_tool;
        if (! $this->consentToolManager->has($name)) {
            return;
        }

        $this->consentToolManager->activate($name, $pageModel, $layoutModel);
    }
}
