<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ContentModel;

final class RenderContentElementListener extends ConsentListener
{
    public function onGetContentElement(ContentModel $contentModel, string $buffer) : string
    {
        return $this->renderForModel($buffer, $contentModel);
    }
}