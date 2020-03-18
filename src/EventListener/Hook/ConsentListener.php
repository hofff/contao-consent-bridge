<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\Model;
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

    public function __construct(
        ConsentToolManager $consentToolManager,
        RequestScopeMatcher $scopeMatcher,
        ConsentIdParser $consentIdParser
    ) {
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

    protected function renderRaw(string $buffer, string $consentIdAsString, Model $model = null) : string
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

        return $consentTool->renderRaw($buffer, $consentId, $model);
    }

    protected function renderContent(string $buffer, string $consentIdAsString, Model $model = null) : string
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

        return $consentTool->renderContent($buffer, $consentId, $model);
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

        return $consentTool->renderRaw($buffer, $consentId);
    }
}
