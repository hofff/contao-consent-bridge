<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\LayoutModel;
use Contao\PageModel;
use Netzmacht\Html\Attributes;

final class GoogleWebfontsListener extends ConsentListener
{
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

        $content = 'https://fonts.googleapis.com/css?family=' . str_replace('|', '%7C', $layoutModel->webfonts);

        // Delete web fonts settings. Detach model to prevent accidentally overwrites.
        $layoutModel->detach();
        $layoutModel->webfonts = '';

        $attributes = new Attributes(
            [
                'rel' => 'stylehseet',
                'href' => $content
            ]
        );

        if (!isset($GLOBALS['TL_HEAD']) || !is_array($GLOBALS['TL_HEAD'])) {
            $GLOBALS['TL_HEAD'] = [];
        }

        $GLOBALS['TL_HEAD'][] = $consentTool->renderStyle($attributes, $consentId);
    }
}