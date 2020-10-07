<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\LayoutModel;
use Contao\PageModel;
use Netzmacht\Html\Attributes;
use function is_array;
use function str_replace;

final class GoogleWebfontsListener extends ConsentListener
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function onGeneratePage(PageModel $pageModel, LayoutModel $layoutModel) : void
    {
        if ($layoutModel->webfonts === '') {
            return;
        }

        $consentTool = $this->consentTool();
        if ($consentTool === null) {
            return;
        }

        $consentId = $consentTool->determineConsentIdByName('google_webfonts');
        if ($consentId === null) {
            return;
        }

        $attributes = new Attributes(
            [
                'rel'  => 'stylesheet',
                'href' => 'https://fonts.googleapis.com/css?family=' . str_replace('|', '%7C', $layoutModel->webfonts),
            ]
        );

        // Delete web fonts settings. Detach model to prevent accidentally overwrites.
        $layoutModel->detach();
        $layoutModel->webfonts = '';

        if (! isset($GLOBALS['TL_HEAD']) || ! is_array($GLOBALS['TL_HEAD'])) {
            $GLOBALS['TL_HEAD'] = [];
        }

        $GLOBALS['TL_HEAD'][] = $consentTool->renderStyle($attributes, $consentId);
    }
}
