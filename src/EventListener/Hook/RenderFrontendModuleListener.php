<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ModuleModel;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use function in_array;

final class RenderFrontendModuleListener extends ConsentListener
{
    /** @var string[] */
    private $modules;

    /** @param string[] $modules */
    public function __construct(
        ConsentToolManager $consentToolManager,
        RequestScopeMatcher $scopeMatcher,
        array $modules
    ) {
        parent::__construct($consentToolManager, $scopeMatcher);

        $this->modules = $modules;
    }

    public function onGetFrontendModule(ModuleModel $moduleModel, string $buffer) : string
    {
        if (!in_array($moduleModel->type, $this->modules, true)) {
            return $buffer;
        }

        return $this->render($buffer, (string) $moduleModel->hofff_consent_bridge_tag);
    }
}
