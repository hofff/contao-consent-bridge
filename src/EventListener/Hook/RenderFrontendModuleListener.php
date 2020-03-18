<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ModuleModel;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use function in_array;

final class RenderFrontendModuleListener extends ConsentListener
{
    /** @var Bridge */
    private $bridge;

    public function __construct(
        ConsentToolManager $consentToolManager,
        RequestScopeMatcher $scopeMatcher,
        ConsentIdParser $consentIdParser,
        Bridge $bridge
    ) {
        parent::__construct($consentToolManager, $scopeMatcher, $consentIdParser);

        $this->bridge = $bridge;
    }

    public function onGetFrontendModule(ModuleModel $moduleModel, string $buffer) : string
    {
        if (!in_array($moduleModel->type, $this->bridge->supportedFrontendModules(), true)) {
            return $buffer;
        }

        return $this->renderContent($buffer, (string) $moduleModel->hofff_consent_bridge_tag, $moduleModel);
    }
}
