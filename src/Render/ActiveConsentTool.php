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
use Override;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
final class ActiveConsentTool implements ConsentTool
{
    public function __construct(
        private readonly ConsentTool $consentTool,
        private readonly ConsentIdParser $consentIdParser,
    ) {
    }

    #[Override]
    public function name(): string
    {
        return $this->consentTool->name();
    }

    #[Override]
    public function activate(
        PageModel $rootPageModel,
        PageModel|null $pageModel = null,
        LayoutModel|null $layoutModel = null,
    ): bool {
        return $this->consentTool->activate($rootPageModel, $pageModel, $layoutModel);
    }

    public function parseConsentId(string $consentId): ConsentId
    {
        return $this->consentIdParser->parse($consentId);
    }

    /** {@inheritDoc} */
    #[Override]
    public function consentIdOptions($context = null): array
    {
        return $this->consentTool->consentIdOptions($context);
    }

    #[Override]
    public function requiresConsent(ConsentId $consentId): bool
    {
        return $this->consentTool->requiresConsent($consentId);
    }

    /** @SuppressWarnings(PHPMD.LongVariable) */
    #[Override]
    public function determineConsentIdByName(string $serviceOrTemplateName): ConsentId|null
    {
        return $this->consentTool->determineConsentIdByName($serviceOrTemplateName);
    }

    #[Override]
    public function renderContent(
        string $buffer,
        ConsentId $consentId,
        Model|null $model = null,
        string|null $placeholderTemplate = null,
    ): string {
        return $this->consentTool->renderContent($buffer, $consentId, $model, $placeholderTemplate);
    }

    #[Override]
    public function renderRaw(string $buffer, ConsentId $consentId, Model|null $model = null): string
    {
        return $this->consentTool->renderRaw($buffer, $consentId, $model);
    }

    #[Override]
    public function renderPlaceholder(string $buffer, ConsentId $consentId): string
    {
        return $this->consentTool->renderPlaceholder($buffer, $consentId);
    }

    #[Override]
    public function renderScript(Attributes $attributes, ConsentId $consentId): string
    {
        return $this->consentTool->renderScript($attributes, $consentId);
    }

    #[Override]
    public function renderStyle(Attributes $attributes, ConsentId $consentId): string
    {
        return $this->consentTool->renderStyle($attributes, $consentId);
    }
}
