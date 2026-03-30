<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

interface WithGenericContextSupport
{
    /**
     * Render content so that consent tool requirements for given consent id are applied.
     *
     * This method might add a placeholder content as it's used for content elements and frontend modules.
     */
    public function renderContentForContext(
        string $buffer,
        ConsentId $consentId,
        array|null $context = null,
        string|null $placeholderTemplate = null,
    ): string;

    /**
     * Apply consent for the given HTML output.
     *
     * Do not add placeholder content here as it might be header code or hidden JavaScript.
     */
    public function renderRawForContext(
        string $buffer,
        ConsentId $consentId,
        array|null $context = null,
    ): string;
}
