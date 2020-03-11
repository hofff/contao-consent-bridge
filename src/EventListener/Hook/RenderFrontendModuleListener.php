<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ModuleModel;

final class RenderFrontendModuleListener extends ConsentListener
{
    public function onGetFrontendModule(ModuleModel $moduleModel, string $buffer) : string
    {
        $consentTool = $this->consentTool();
        if ($consentTool === null) {
            return $buffer;
        }

        return $consentTool->renderFrontendModule($moduleModel, $buffer);
    }
}