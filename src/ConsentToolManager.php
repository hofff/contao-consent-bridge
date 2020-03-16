<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

final class ConsentToolManager
{
    /** @var ConsentTool|null */
    private $activeConsentTool;

    /** @var ConsentTool[] */
    private $consentTools = [];

    /** @return ConsentTool[] */
    public function consentTools() : array
    {
        return $this->consentTools;
    }

    public function register(ConsentTool $consentTool) : void
    {
        $this->consentTools[$consentTool->name()] = $consentTool;
    }

    public function activate(string $name) : void
    {
        if (!isset($this->consentTools[$name])) {
            throw new \InvalidArgumentException(sprintf('Consent tool "%s" is not known', $name));
        }
    }

    public function hasActiveConsentTool() : bool
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
