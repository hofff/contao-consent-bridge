<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Render;

use Contao\LayoutModel;
use Contao\Model;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Netzmacht\Html\Attributes;

final class ActiveConsentTool implements ConsentTool
{
    /** @var ConsentTool */
    private $consentTool;

    /** @var ConsentIdParser */
    private $consentIdParser;

    public function __construct(ConsentTool $consentTool, ConsentIdParser $consentIdParser)
    {
        $this->consentTool     = $consentTool;
        $this->consentIdParser = $consentIdParser;
    }

    public function name() : string
    {
        return $this->consentTool->name();
    }

    public function activate(
        PageModel $rootPageModel,
        ?PageModel $pageModel = null,
        ?LayoutModel $layoutModel = null
    ) : bool {
        return $this->consentTool->activate($rootPageModel, $pageModel, $layoutModel);
    }

    public function parseConsentId(string $consentId) : ConsentId
    {
        return $this->consentIdParser->parse($consentId);
    }

    /** {@inheritDoc} */
    public function consentIdOptions($context = null) : array
    {
        return $this->consentTool->consentIdOptions($context);
    }

    public function requiresConsent(ConsentId $consentId) : bool
    {
        return $this->consentTool->requiresConsent($consentId);
    }

    public function determineConsentIdByName(string $serviceOrTemplateName) : ?ConsentId
    {
        return $this->consentTool->determineConsentIdByName($serviceOrTemplateName);
    }

    public function renderContent(
        string $buffer,
        ConsentId $consentId,
        ?Model $model = null,
        ?string $placeholderTemplate = null
    ) : string {
        return $this->consentTool->renderContent($buffer, $consentId, $model, $placeholderTemplate);
    }

    public function renderRaw(string $buffer, ConsentId $consentId, ?Model $model = null) : string
    {
        return $this->consentTool->renderRaw($buffer, $consentId, $model);
    }

    public function renderPlaceholder(string $buffer, ConsentId $consentId) : string
    {
        return $this->consentTool->renderPlaceholder($buffer, $consentId);
    }

    public function renderScript(Attributes $attributes, ConsentId $consentId) : string
    {
        return $this->consentTool->renderScript($attributes, $consentId);
    }

    public function renderStyle(Attributes $attributes, ConsentId $consentId) : string
    {
        return $this->consentTool->renderStyle($attributes, $consentId);
    }
}
