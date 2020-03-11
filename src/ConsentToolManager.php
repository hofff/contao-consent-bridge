<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

final class ConsentToolManager
{
    /** @var ConsentTool|null */
    private $activeConsentTool;

    public function activate(ConsentTool $consentTool) : void
    {
        $this->activeConsentTool = $consentTool;
    }

    public function isActive() : bool
    {
        if ($this->activeConsentTool === null) {
            return false;
        }

        return true;
    }

    public function activeConsentTool() :? ConsentTool
    {
        return $this->activeConsentTool;
    }
}