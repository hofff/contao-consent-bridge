<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Twig;

use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;
use Override;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Twig\Extension\AbstractExtension;
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
    public function getTokenParsers(): array
    {
        return [new ConsentTokenParser()];
    }

    /** {@inheritDoc} */
    #[Override]
    public function getFunctions(): array
    {
        return [new TwigFunction('hofff_consent_required', $this->isConsentRequired(...))];
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
}
