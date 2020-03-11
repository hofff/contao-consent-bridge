<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Contao\ContentModel;

interface ConsentTool
{
    public function name() : string;

    public function supportedContentElements() : array;

    public function supportedFrontendModules() : array;

    public function supportedTemplates(): void;

    public function renderContentElement(ContentModel $contentModel, string $buffer) : string;

    public function renderFrontendModule(\Contao\ModuleModel $moduleModel, string $buffer) : string;
}