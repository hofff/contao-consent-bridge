<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\Model;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;
use Hofff\Contao\Consent\Bridge\Render\RenderInformation;
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

    protected function renderContent(
        string $buffer,
        string $consentIdAsString,
        RenderInformation $renderInformation,
        Model $model = null
    ) : string {
        if (! $renderInformation->isAutoRenderMode()) {
            return $buffer;
        }

        $consentTool = $this->consentTool();
        if ($consentTool === null) {
            return $buffer;
        }

        try {
            $consentId = $this->consentIdParser->parse($consentIdAsString);
        } catch (InvalidArgumentException $exception) {
            return $buffer;
        }

        if (! $consentTool->requiresConsent($consentId)) {
            return $buffer;
        }

        return $consentTool->renderContent($buffer, $consentId, $model, $renderInformation->placeholderTemplate());
    }

    protected function renderForTemplate(string $buffer, string $templateName) : string
    {
        $consentTool = $this->consentTool();
        if ($consentTool === null) {
            return $buffer;
        }

        $consentId = $consentTool->determineConsentIdByName($templateName);
        if ($consentId === null || ! $consentTool->requiresConsent($consentId)) {
            return $buffer;
        }

        return $consentTool->renderRaw($buffer, $consentId);
    }
}
