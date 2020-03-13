<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

final class ConsentToolManager
{
    /** @var string[] */
    private $frontendModules;

    /** @var string[] */
    private $contentElements;

    /** @var ConsentTool|null */
    private $activeConsentTool;

    /**
     * @param string[] $frontendModules
     * @param string[] $contentElements
     */
    public function __construct(array $frontendModules, array $contentElements)
    {
        $this->frontendModules = $frontendModules;
        $this->contentElements = $contentElements;
    }

    public function supportedContentElements() : array
    {
        return $this->contentElements;
    }

    public function supportedFrontendModules() : array
    {
        return $this->frontendModules;
    }

    public function activate(ConsentTool $consentTool) : void
    {
        $this->activeConsentTool = $consentTool;
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