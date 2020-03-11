<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Contao\ContentModel;
use Contao\Model;

interface ConsentTool
{
    public function name() : string;

    public function supportedContentElements() : array;

    public function supportedFrontendModules() : array;

    public function supportedTemplates(): void;

    public function determineConsentIdFromModel(Model $model) : ?ConsentId;

    public function requiresConsent(ConsentId $consentId) : bool;

    public function renderHtml(string $buffer, ConsentId $consentId) : string;

    public function renderScript(string $buffer, ConsentId $consentId) : string;

    public function renderStyle(string $buffer, ConsentId $consentId) : string;
}