<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

interface Plugin
{
    public function providedConsentIds() : array;

    public function supportedContentElements() : array;

    public function supportedFrontendModules() : array;
}
