<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use InvalidArgumentException;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;

abstract class ConsentListener
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    /** @var RequestScopeMatcher */
    private $scopeMatcher;

    /** @var ConsentIdParser */
    private $consentIdParser;

    public function __construct(ConsentToolManager $consentToolManager, RequestScopeMatcher $scopeMatcher, ConsentIdParser $consentIdParser)
    {
        $this->consentToolManager = $consentToolManager;
        $this->scopeMatcher       = $scopeMatcher;
        $this->consentIdParser    = $consentIdParser;
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

        try {
            $consentId = $this->consentIdParser->parse($consentIdAsString);
        } catch (InvalidArgumentException $exception) {
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
