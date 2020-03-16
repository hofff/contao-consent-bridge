<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\Model;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;

abstract class ConsentListener
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    /** @var RequestScopeMatcher */
    private $scopeMatcher;

    public function __construct(ConsentToolManager $consentToolManager, RequestScopeMatcher $scopeMatcher)
    {
        $this->consentToolManager = $consentToolManager;
        $this->scopeMatcher       = $scopeMatcher;
    }

    protected function consentTool() : ?ConsentTool
    {
        if (! $this->scopeMatcher->isFrontendRequest()) {
            return null;
        }

        return $this->consentToolManager->activeConsentTool();
    }

    protected function render(string $buffer, string $consentIdAsString) : string
    {
        $consentTool = $this->consentTool();
        if ($consentTool === null) {
            return $buffer;
        }

        $consentId = $consentTool->createConsentId($consentIdAsString);
        if ($consentId === null) {
            return $buffer;
        }

        return $consentTool->renderHtml($buffer, $consentId);
    }

    protected function renderForTemplate(string $buffer, string $templateName) : string
    {
        $consentTool = $this->consentTool();
        if ($consentTool === null) {
            return $buffer;
        }

        $consentId = $consentTool->determineConsentIdByName($templateName);
        if ($consentId === null) {
            return $buffer;
        }

        return $consentTool->renderHtml($buffer, $consentId);
    }
}
