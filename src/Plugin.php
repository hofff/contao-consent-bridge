<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Hofff\Contao\Consent\Bridge\ConsentId;

interface Plugin
{
    /** @psalm-return list<class-string<ConsentId>> */
    public function providedConsentIds() : array;

    /** @psalm-return list<string> */
    public function supportedContentElements() : array;

    /** @psalm-return list<string> */
    public function supportedFrontendModules() : array;
}
