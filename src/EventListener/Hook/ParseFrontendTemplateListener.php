<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

final class ParseFrontendTemplateListener extends ConsentListener
{
    public function onParseFrontendTemplate(string $buffer, string $templateName) : string
    {
        return $this->renderForTemplate($buffer, $templateName);
    }
}
