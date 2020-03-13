<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Contao\Model;
use Netzmacht\Html\Attributes;

interface ConsentTool
{
    public function name() : string;

    public function determineConsentIdByName(string $serviceOrTemplateName) : ?ConsentId;

    public function determineConsentIdFromModel(Model $model) : ?ConsentId;

    public function requiresConsent(ConsentId $consentId) : bool;

    public function renderHtml(string $buffer, ConsentId $consentId) : string;

    public function renderScript(Attributes $attributes, ConsentId $consentId) : string;

    public function renderStyle(Attributes $attributes, ConsentId $consentId) : string;
}