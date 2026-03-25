<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Twig;

use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;
use Hofff\Contao\Consent\Bridge\Exception\RuntimeException;
use Hofff\Contao\Consent\Bridge\WithGenericContextSupport;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

use function is_array;

#[AsTaggedItem('twig.extension')]
final class TwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly ConsentToolManager $consentToolManager,
        private readonly ConsentIdParser $consentIdParser,
    ) {
    }

    /** {@inheritDoc} */
    #[Override]
    public function getFunctions(): array
    {
        return [new TwigFunction('hofff_consent_required', $this->isConsentRequired(...))];
    }

    /** {@inheritDoc} */
    #[Override]
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'hofff_consent_content',
                $this->renderContent(...),
                ['needs_context' => true, 'is_safe' => ['html']],
            ),
            new TwigFilter(
                'hofff_consent_raw',
                $this->renderContent(...),
                ['needs_context' => true, 'is_safe' => ['html']],
            ),
        ];
    }

    /** @param string|array{hofff_consent_bridge_tag?: string} $consentIdContext */
    public function isConsentRequired(string|array $consentIdContext): bool
    {
        $consentTool = $this->consentToolManager->activeConsentTool();
        if ($consentTool === null) {
            return false;
        }

        if (is_array($consentIdContext)) {
            $consentIdContext = $consentIdContext['hofff_consent_bridge_tag'] ?? '';
        }

        try {
            $consentId = $this->consentIdParser->parse($consentIdContext);
        } catch (InvalidArgumentException) {
            return false;
        }

        return $consentTool->requiresConsent($consentId);
    }

    /**
     * @param array<string, mixed> $context
     * @param array<string, mixed> $data
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function renderContent(
        array $context,
        string $html,
        string $consentId,
        string|null $placeholderTemplate = null,
        array|null $data = null,
    ): string {
        $consentTool = $this->getActiveConsentTool();
        if ($consentTool === null) {
            return $html;
        }

        try {
            $consentId = $this->consentIdParser->parse($consentId);
        } catch (InvalidArgumentException) {
            return $html;
        }

        return $consentTool->renderContentForContext($html, $consentId, $data ?? $context, $placeholderTemplate);
    }

    /**
     * @param array<string, mixed> $context
     * @param array<string, mixed> $data
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function renderRaw(array $context, string $html, string $consentId, array|null $data = null): string
    {
        $consentTool = $this->getActiveConsentTool();
        if ($consentTool === null) {
            return $html;
        }

        try {
            $consentId = $this->consentIdParser->parse($consentId);
        } catch (InvalidArgumentException) {
            return $html;
        }

        return $consentTool->renderRawForContext($html, $consentId, $data ?? $context);
    }

    public function getActiveConsentTool(): WithGenericContextSupport|null
    {
        $consentTool = $this->consentToolManager->activeConsentTool();
        if ($consentTool === null) {
            return null;
        }

        if (! $consentTool instanceof WithGenericContextSupport) {
            throw new RuntimeException('Consent tool does not support Twig.');
        }

        return $consentTool;
    }
}
