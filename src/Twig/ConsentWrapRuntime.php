<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Twig;

use Contao\ContentModel;
use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;
use Twig\Extension\RuntimeExtensionInterface;

use function is_string;

final readonly class ConsentWrapRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private ConsentToolManager $consentToolManager,
        private ConsentIdParser $consentIdParser,
    ) {
    }

    public function wrapContent(
        string $content,
        ConsentId|string|null $consentId = null,
        array|null $model = null,
        string|null $placeholderTemplate = null,
    ): string {
        if ($consentId === null) {
            return $content;
        }

        if (is_string($consentId)) {
            try {
                $consentId = $this->consentIdParser->parse($consentId);
            } catch (InvalidArgumentException) {
                return $content;
            }
        }

        $consentTool = $this->consentToolManager->activeConsentTool();
        if ($consentTool === null) {
            return $content;
        }

        if ($model !== null) {
            $model = new ContentModel($model);
            $model->detach(false);
        }

        return $consentTool->renderContent($content, $consentId, $model, $placeholderTemplate);
    }
}
