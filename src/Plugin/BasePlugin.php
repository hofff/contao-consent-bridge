<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Plugin;

use Hofff\Contao\Consent\Bridge\Plugin;

/** @deprecated Will be removed in version 2.0 */
abstract class BasePlugin implements Plugin
{
    /** @inheritDoc */
    public function providedConsentIds(): array
    {
        return [];
    }

    /** @inheritDoc */
    public function supportedContentElements(): array
    {
        return [];
    }

    /** @inheritDoc */
    public function supportedFrontendModules(): array
    {
        return [];
    }
}
