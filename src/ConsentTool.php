<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Contao\DataContainer;
use Contao\LayoutModel;
use Contao\PageModel;
use Netzmacht\Html\Attributes;

interface ConsentTool
{
    public function name() : string;

    public function configure(PageModel $pageModel, LayoutModel $layoutModel) : void;

    /**
     * Get consent id options by name and consent ids
     *
     * @param DataContainer|object|null $context
     *
     * @return ConsentId[]|array<string, ConsentId>
     */
    public function consentIdOptions($context = null) : array;

    public function determineConsentIdByName(string $serviceOrTemplateName) : ?ConsentId;

    public function createConsentId(string $name) : ?ConsentId;

    public function requiresConsent(ConsentId $consentId) : bool;

    public function renderHtml(string $buffer, ConsentId $consentId) : string;

    public function renderScript(Attributes $attributes, ConsentId $consentId) : string;

    public function renderStyle(Attributes $attributes, ConsentId $consentId) : string;
}
