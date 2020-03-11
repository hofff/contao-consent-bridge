<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

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

}